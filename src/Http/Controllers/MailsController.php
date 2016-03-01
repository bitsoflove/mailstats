<?php

namespace BitsOfLove\MailStats\Http\Controllers;

use BitsOfLove\MailStats\LogResponse;
use BitsOfLove\MailStats\SendMail;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class MailsController extends Controller
{

    /**
     * @var \Monolog\Logger;
     */
    protected $logger;

    /**
     * MailsController constructor.
     */
    public function __construct()
    {
        $this->logger = app('mail-stats.logger');
    }

    /**
     * Entry point to send emails via Mailgun
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mail(Request $request)
    {
        $service = SendMail::create($request);
        $service->send();

        return new \Illuminate\Http\JsonResponse(true);
    }

    /**
     * Entry point for the Mailgun webhooks
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function log(Request $request)
    {
        // lets log the full response just to be sure
        // but only not in production
        if (!app()->environment('production')) {
            $this->logger->addInfo(json_encode($request->request->all()));
        }

        $responseLogger = LogResponse::create($request);
        $responseLogger->log();

        return new \Illuminate\Http\JsonResponse(true);
    }
}
