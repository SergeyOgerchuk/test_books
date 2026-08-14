<?php

namespace app\services;

use Yii;
use yii\base\Component;

/**
 * Для тестового задания запрос к smspilot выполняется синхронно
 * В проде отправку следовало бы вынести в очередь
 */
class SmsPilotService extends Component implements SmsSenderInterface
{
    public string $apiKey;
    public bool $testMode = true;

    public function send(string $phone, string $message): bool
    {
        $phone = preg_replace('/\D+/', '', $phone);

        $url = 'https://smspilot.ru/api.php?' . http_build_query([
            'send' => $message,
            'to' => $phone,
            'apikey' => $this->apiKey,
            'format' => 'json',
            'test' => $this->testMode ? 1 : 0,
        ]);

        try {
            $response = file_get_contents($url);

            if ($response === false) {
                Yii::warning('Не удалось получить ответ от smspilot');

                return false;
            }

            $result = json_decode($response, true);

            if (!is_array($result)) {
                Yii::warning('smspilot вернул некорректный JSON');

                return false;
            }

            if (isset($result['error'])) {
                Yii::warning($result['error']['description_ru'] ?? 'Ошибка smspilot');

                return false;
            }

            return isset($result['send']);
        } catch (\Throwable $e) {
            Yii::warning($e->getMessage());

            return false;
        }
    }
}
