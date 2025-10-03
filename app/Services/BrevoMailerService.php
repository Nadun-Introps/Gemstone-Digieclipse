<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use GuzzleHttp\Client;

class BrevoMailerService
{
    protected $apiInstance;
    protected $config;

    public function __construct()
    {
        $this->config = Configuration::getDefaultConfiguration()
            ->setApiKey('api-key', config('brevo.api_key'));

        $this->apiInstance = new TransactionalEmailsApi(
            new Client(),
            $this->config
        );
    }

    /**
     * Send transactional email using Brevo
     */
    public function sendEmail($to, $subject, $htmlContent, $params = [])
    {
        try {
            $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                'sender' => [
                    'name' => config('brevo.sender.name'),
                    'email' => config('brevo.sender.email')
                ],
                'to' => [[
                    'email' => $to['email'],
                    'name' => $to['name'] ?? $to['email']
                ]],
                'subject' => $subject,
                'htmlContent' => $htmlContent,
                'params' => $params
            ]);

            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);

            Log::info('Brevo email sent successfully', [
                'to' => $to['email'],
                'message_id' => $result->getMessageId(),
                'subject' => $subject
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Brevo email sending failed: ' . $e->getMessage(), [
                'to' => $to['email'],
                'subject' => $subject
            ]);
            return false;
        }
    }

    /**
     * Send email using Brevo template
     */
    public function sendTemplateEmail($to, $templateId, $templateData = [])
    {
        try {
            $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail([
                'sender' => [
                    'name' => config('brevo.sender.name'),
                    'email' => config('brevo.sender.email')
                ],
                'to' => [[
                    'email' => $to['email'],
                    'name' => $to['name'] ?? $to['email']
                ]],
                'templateId' => $templateId,
                'params' => $templateData
            ]);

            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);

            Log::info('Brevo template email sent successfully', [
                'to' => $to['email'],
                'template_id' => $templateId,
                'message_id' => $result->getMessageId()
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Brevo template email sending failed: ' . $e->getMessage(), [
                'to' => $to['email'],
                'template_id' => $templateId
            ]);
            return false;
        }
    }
}
