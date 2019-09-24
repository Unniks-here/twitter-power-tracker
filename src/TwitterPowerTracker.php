<?php
namespace unniks\TwitterPowertracker;
use Illuminate\Contracts\Config\Repository;
use App\TwitterPowerTrackerStream;
use Exception;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Catch_;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;

class TwitterPowerTracker
{
    /** @var array */
    protected $config;
    protected $url;
    protected $pass;
    protected $login;
    protected $buffersize;

    public function __construct(Repository $config)
    {
        $this->config = $config['power-tracker-config.php'];
        $this->login=$this->config['twitter_gnip_username'];
        $this->pass=$this->config['twitter_gnip_password'];
        $this->buffersize = 2000;
    }

    protected function stream()
    {
        try{
            $curl = curl_init();
            curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->url,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_USERPWD => "$this->login:$this->pass",
                    CURLOPT_ENCODING => 'gzip, deflate',
                   // CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_BUFFERSIZE => $this->buffersize,
                    CURLOPT_WRITEFUNCTION => array($this, "callback")
                )
            );
            curl_exec($curl);
            curl_close($curl);
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
            self:stream();
        }
    }

    public function powerStream()
    {
        $this->url=$this->config['twitter_gnip_url'];
        $this->stream();
    }

    public function powerReplayStream()
    {
    }

    public function power30DaysStream()
    {
    }

    public function callback($curl, $data)
    {
        ob_get_clean();
        if (($data === false) || ($data == null))
        {
            throw new \Exception (curl_error($curl) . " " . curl_errno($curl));
        }
        $length = strlen($data);
       // echo $data;
        $this->dataport($data);
        // ob_flush();
        flush();
        return $length;
    }

    protected function dataport($data)
    {
        try{
            TwitterPowerTrackerStream::getPowerTrack($data);
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
            throw new \Exception ($e->getMessage());
        }
    }

    //Create rules using this function
    public function ruleCreation($json)
    {
        try{
            $ch = curl_init();
            $this->url=$this->config['twitter_gnip_rules_url'];
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,CURLOPT_USERPWD,$this->login.':'.$this->pass);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return $result;
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
            return $e->getMessage();
        }
    }

    //Create rules using this function
    public function ruleDeletion($json)
    {
        try{
            $ch = curl_init();
            $this->url=$this->config['twitter_gnip_rules_url'].'?_method=delete';
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,CURLOPT_USERPWD,$this->login.':'.$this->pass);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_POST, 1);

            $headers = array();
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return $result;
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
            return $e->getMessage();
        }
    }
}
