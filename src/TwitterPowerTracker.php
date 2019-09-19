<?php
namespace unniks\TwitterPowertracker;
use Illuminate\Contracts\Config\Repository;
use App\TwitterPowerTrackerStream;

class TwitterPowerTracker
{
    /** @var array */
    protected $config;
    protected $url;
    protected $pass;
    protected $login;
    protected $track_type;

    public function __construct(Repository $config)
    {
        $this->config = $config['power-tracker-config.php'];
        $this->login=$this->config['twitter_gnip_username'];
        $this->pass=$this->config['twitter_gnip_password'];
    }

    protected function stream()
    {
        // $this->login='arun@maybe.xyz';
        // $this->pass='Qazxsw321$';
        $curl = curl_init();
        curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_USERPWD => "$this->login:$this->pass",
                CURLOPT_ENCODING => 'gzip, deflate',
               // CURLOPT_FOLLOWLOCATION => 1,
              //  CURLOPT_BUFFERSIZE => $this->buffersize,
                CURLOPT_WRITEFUNCTION => array($this, "callback")
            )
        );
        curl_exec($curl);
        curl_close($curl);
    }

    public function powerDevStream()
    {
        $this->url=$this->config['twitter_gnip_dev_url'];
        $this->track_type='dev';
        $this->stream();
    }

    public function powerProdStream()
    {
        $this->url=$this->config['twitter_gnip_dev_url'];
        $this->track_type='prod';
        $this->stream();
    }

    public function powerReplayStream()
    {
        $this->track_type='replay';
    }

    public function power30DaysStream()
    {
        $this->track_type='30days';
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
        TwitterPowerTrackerStream::getPowerTrackDev($data);
        // ob_flush();
        flush();
        return $length;
    }

    protected function dataport($data)
    {
        switch($this->track_type)
        {
            case 'dev':
                TwitterPowerTrackerStream::getPowerTrackDev($data);
                break;
            case 'prod':
                TwitterPowerTrackerStream::getPowerTrackProd($data);
                break;
            default:
                break;
        }
    }
}
