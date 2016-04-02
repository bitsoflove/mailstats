<?php

namespace BitsOfLove\MailStats\Http\Controllers;

use BitsOfLove\MailStats\Exceptions\ProjectNotSupported;
use BitsOfLove\MailStats\LogResponse;
use BitsOfLove\MailStats\SendMail;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function mail(Request $request)
    {
        try {
            $service = SendMail::create($request);
            $service->send();
        } catch (ProjectNotSupported $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    "The project you've provided could not be found in our records"
                ]
            ], 400);
        } catch (ValidationException $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => $e->errors(),
            ], 400);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    $e->getMessage()
                ],
            ], 400);
        }

        return new JsonResponse([
            'success' => true
        ]);
    }

    /**
     * Entry point for the Mailgun webhooks
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function log(Request $request)
    {
        // lets log the full response just to be sure
        // but only if we aren't in production environment
        if (!app()->environment('production')) {
            $this->logger->addInfo(json_encode($request->request->all()));
        }

        try {
            $responseLogger = LogResponse::create($request);
            $responseLogger->log();
        } catch (ProjectNotSupported $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    "The project you've provided could not be found in our records"
                ]
            ], 400);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    $e->getMessage()
                ],
            ], 400);
        }

        return new JsonResponse([
            'success' => true
        ]);
    }
}
