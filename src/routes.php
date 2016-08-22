<?php
/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
use LINE\LINEBot;
use LINE\LINEBot\Message\MultipleMessages;
use LINE\LINEBot\Receive\Receive;
use LINE\LINEBot\Receive\Message;
use LINE\LINEBot\Receive\Message\Contact;
use LINE\LINEBot\Receive\Message\Location;
use LINE\LINEBot\Receive\Message\Sticker;
use LINE\LINEBot\Receive\Message\Text;
use LINE\LINEBot\Receive\Operation;
use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/callback', function (Request $req, Response $res, $arg) {
    $body = $req->getBody();
    $signatureHeader = $req->getHeader('X-LINE-ChannelSignature');
    if (empty($signatureHeader) || !$this->bot->validateSignature($body, $signatureHeader[0])) {
        return $res->withStatus(400, "Bad Request");
    }

    /** @var LINEBot $bot */
    $bot = $this->bot;

    /** @var Receive[] $receives */
    $receives = $bot->createReceivesFromJSON($body);
    foreach ($receives as $receive) {
        if ($receive->isMessage()) {
            /** @var Message $receive */

            $this->logger->info(sprintf(
                'contentId=%s, fromMid=%s, createdTime=%s, msg=%s',
                $receive->getContentId(),
                $receive->getFromMid(),
                $receive->getCreatedTime(),
                $receive->getText()
            ));
            try{
                $bot->sendText($receive->getFromMid(), $receive->getText());
            }catch(Exception $e)
            {
                 $this->logger->info(sprintf(
                    'ex=%s',
                    $e->getMessage()
                ));

            }
        }
    }

    r1eturn $res->getBody()->write("OK");
});

$app->get('/', function (Request $req, Response $res, $arg) {
    $bot = $this->bot;
    $bot->sendText("u4983945981742f76022547cb0bf32f53", "tin nhan gui tu bot");    
    return "hello world";
   
});
$app->get('/home', function (Request $req, Response $res, $arg) {
    return "hello world home";
   
});

