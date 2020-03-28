<?php
 
namespace App\Http\Controllers;
 
use App\Gateway\EventLogGateway;
use App\Gateway\QuestionGateway;
use App\Gateway\UserGateway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
 
class Webhook extends Controller
{
    /**
     * @var LINEBot
     */
    private $bot;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Response
     */
    private $response;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var EventLogGateway
     */
    private $logGateway;
    /**
     * @var UserGateway
     */
    private $userGateway;
    /**
     * @var QuestionGateway
     */
    private $questionGateway;
    /**
     * @var array
     */
    private $user;
 
 
    public function __construct(
        Request $request,
        Response $response,
        Logger $logger,
        EventLogGateway $logGateway,
        UserGateway $userGateway,
        QuestionGateway $questionGateway
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->logger = $logger;
        $this->logGateway = $logGateway;
        $this->userGateway = $userGateway;
        $this->questionGateway = $questionGateway;
 
        // create bot object
        $httpClient = new CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
        $this->bot  = new LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
    }

    public function __invoke()
    {
        // get request
        $body = $this->request->all();
    
        // debuging data
        $this->logger->debug('Body', $body);
    
        // save log
        $signature = $this->request->server('HTTP_X_LINE_SIGNATURE') ?: '-';
        $this->logGateway->saveLog($signature, json_encode($body, true));
    
        return $this->handleEvents();
    }

    private function handleEvents()
    {
        $data = $this->request->all();
    
        if(is_array($data['events'])){
            foreach ($data['events'] as $event)
            {
                // skip group and room event
                if(! isset($event['source']['userId'])){
                    // get user data from database
                    $this->user = $this->userGateway->getUser($event['source']['userId']);
                    
                    // if user not registered
                            if(!$this->user) $this->followCallback($event);
                            else {
                                // respond event
                                if($event['type'] == 'message'){
                                    if(method_exists($this, $event['message']['type'].'Message')){
                                        $this->{$event['message']['type'].'Message'}($event);
                                    }
                                } else {
                                    if(method_exists($this, $event['type'].'Callback')){
                                        $this->{$event['type'].'Callback'}($event);
                                    }
                                }
                            }
                }
                else if ($event['source']['type'] == 'group' or
                $event['source']['type'] == 'room'){
                    
                }
            }
        }
    
    
        $this->response->setContent("No events found!");
        $this->response->setStatusCode(200);
        return $this->response;
    }

    private function followCallback($event)
    {
        $res = $this->bot->getProfile($event['source']['userId']);
        if ($res->isSucceeded())
        {
            $profile = $res->getJSONDecodedBody();
    
            // create welcome message
            $message  = "Salam kenal, " . $profile['displayName'] . "!\n";
            $message .= "Aku akan membantu kamu memberikan informasi tentang COVID-19 terkini";
            $textMessageBuilder = new TextMessageBuilder($message);

            $message2 = "Untuk melihat angka penyebaran COVID-19 pada berbagai negara silahkan ketikkan nama negara. Contoh : USA";
            $textMessageBuilder2 = new TextMessageBuilder($message2);

            $message3 = "Atau kamu dapat mengikuti kuis seputar fakta COVID-19 dengan mengirim pesan \"MULAI\"";
            $textMessageBuilder3 = new TextMessageBuilder($message3);
    
            // create sticker message
            $stickerMessageBuilder = new StickerMessageBuilder(1, 407);
    
            // merge all message
            $multiMessageBuilder = new MultiMessageBuilder();
            $multiMessageBuilder->add($textMessageBuilder);
            $multiMessageBuilder->add($textMessageBuilder2);
            $multiMessageBuilder->add($textMessageBuilder3);
            $multiMessageBuilder->add($stickerMessageBuilder);
    
            // send reply message
            $this->bot->replyMessage($event['replyToken'], $multiMessageBuilder);
    
            // save user data
            $this->userGateway->saveUser(
                $profile['userId'],
                $profile['displayName']
            );
    
        }
    }

}