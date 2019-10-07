<?php
namespace unniks\TwitterPowertracker;
use Illuminate\Contracts\Config\Repository;
use App\TwitterPowerTrackerStream;
use DateTime;
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
    private $curl ;
    private $exit;

    public function __construct(Repository $config)
    {
        $this->config = $config['power-tracker-config.php'];
        $this->login=$this->config['twitter_gnip_username'];
        $this->pass=$this->config['twitter_gnip_password'];
        $this->buffersize = 2000;
        $this->exit='no';
    }

    protected function stream()
    {
        try{
            $this->curl = curl_init();
            curl_setopt_array($this->curl, array(
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
            curl_exec($this->curl);
            curl_close($this->curl);
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }
    }

    public function powerStream()
    {
        $this->url=$this->config['twitter_gnip_url'];
        
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
        $returned = $this->dataport($data);

        flush();
        if($returned == 'exit')
        {
            $this->exit='exit';
            return 0;
        }
        // ob_flush();
        return $length;
    }

    protected function dataport($data)
    {
        try{
            return TwitterPowerTrackerStream::getPowerTrack($data);
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

    public function thirtyDayCurlExce($json)
    {
        try{
            $ch = curl_init();
            $this->url=$this->config['twitter_gnip_30_days_url'];
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
                Log::error( 'Error:' . curl_error($ch));
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

    function __call($name,$arg){
        if($name == 'ruleCreationByArray')
        {
            switch(count($arg))
            {
                    case 1 :
                        try{
                            $rules=array();
                            $values =array();
                            $rules_array=$arg[0];
                            foreach($rules_array as $key=>$rule)
                            {
                                $values[]=['value'=>$rule];
                            }

                            $rules['rules']=$values;

                            $rule_v=json_encode($rules);

                            return self::ruleCreation($rule_v);
                        }
                        catch(Exception $e)
                        {
                            Log::error($e);
                        }
                    break;
                    case 2 :
                        try{
                            $rules=array();
                            $values =array();
                            $rules_array=$arg[0];
                            $tags_array=$arg[1];
                            foreach($rules_array as $key=>$rule)
                            {
                                $values[]=['value'=>$rule,'tag'=>$tags_array[$key]];
                            }

                            $rules['rules']=$values;

                            $rule_v=json_encode($rules);

                            return self::ruleCreation($rule_v);
                        }
                        catch(Exception $e)
                        {
                            Log::error($e);
                        }
                    break;
                    default : throw new \Exception ('invalid number of arguments supplied');
            }
        }
        if($name == 'ruleCreationByJson')
        {
            switch(count($arg))
            {
                    case 1 :
                        return self::ruleCreation($rule_v);
                    default : throw new \Exception ('invalid number of arguments supplied');
            }
        }
        if($name == 'ruleDeletionByArray')
        {
            switch(count($arg))
            {
                    case 1 :
                        try{
                            $rules=array();
                            $values =array();
                            $rules_array=$arg[0];
                            foreach($rules_array as $key=>$rule)
                            {
                                $values[]=['value'=>$rule];
                            }

                            $rules['rules']=$values;

                            $rule_v=json_encode($rules);

                            return self::ruleDeletion($rule_v);
                        }
                        catch(Exception $e)
                        {
                            Log::error($e);
                        }
                    break;
                    case 2 :
                        try{
                            $rules=array();
                            $values =array();
                            $rules_array=$arg[0];
                            $tags_array=$arg[1];
                            foreach($rules_array as $key=>$rule)
                            {
                                $values[]=['value'=>$rule,'tag'=>$tags_array[$key]];
                            }

                            $rules['rules']=$values;

                            $rule_v=json_encode($rules);

                            return self::ruleDeletion($rule_v);
                        }
                        catch(Exception $e)
                        {
                            Log::error($e);
                        }
                    break;
                    default : throw new \Exception ('invalid number of arguments supplied');
                    ;
            }
        }
        if($name == 'ruleDeletionByJson')
        {
            switch(count($arg))
            {
                    case 1 :
                            return self::ruleDeletion($arg[0]);
                    default : throw new \Exception ('invalid number of arguments supplied');
                    ;
            }
        }
        if($name == 'thirtyDaysGet')
        {
            $json = [];
            switch(count($arg))
            {
                    case 1 :
                            $rule = $arg[0];
                            $json['query'] = $rule;
                            break;
                    case 2 :
                            $rule = $arg[0];
                            $maxResults = $arg[1];
                            $json['query'] = $rule;
                            if($maxResults!=-1)
                            $json['maxResults'] = $maxResults;
                            break;
                    case 4 :
                            $rule = $arg[0];
                            $maxResults = $arg[1];
                            $startDate = $arg[2];
                            $endDate = $arg[3];

                            $startDate = new DateTime($startDate);
                            $startDate = $startDate->format('YmdHi');

                            $endDate = new DateTime($endDate);
                            $endDate = $endDate->format('YmdHi');

                            $json['query'] = $rule;
                            if($maxResults!=-1)
                            $json['maxResults'] = $maxResults;
                            $json['fromDate'] = $startDate;
                            $json['toDate'] = $endDate;
                            break;

                    case 4 :

                            $rule = $arg[0];
                            $maxResults = $arg[1];
                            $startDate = $arg[2];
                            $endDate = $arg[3];

                            $startDate = new DateTime($startDate);
                            $startDate = $startDate->format('YmdHis');

                            $endDate = new DateTime($endDate);
                            $endDate = $endDate->format('YmdHis');

                            $next = $arg[4];

                            $json['query'] = $rule;
                            if($maxResults!=-1)
                            $json['maxResults'] = $maxResults;
                            $json['fromDate'] = $startDate;
                            $json['toDate'] = $endDate;
                            $json['next'] = $next;
                            break;


                    default : throw new \Exception ('invalid number of arguments supplied');
                    ;
            }

            $json = json_encode($json);
            return self::thirtyDayCurlExce($json);
        }
    }
}
