<?php

namespace Smiile;

use RuntimeException;

class SubscribeHelper
{
    const APP_URL = "https://app.smiile.com";
    const APP_URL_DEMO = "https://demo-app.smiile.com";

    const VERSION_1 = 1;
    const VERSION_2 = 2;

    const DEFINITION_AND_ORDER = [
        self::VERSION_1 => [
            "address",
            "civility",
            "name",
            "lastName",
            "mail",
        ],
        self::VERSION_2 => [
            "address",
            "name",
            "lastName",
            "mail",
        ],
    ];

    /** @var string */
    private $providerKey;
    /** @var string */
    private $secretCode;

    /** @var bool */
    private $demoMode = false;

    /** @var int */
    private $version;

    public function __construct(string $providerKey, string $secretCode, int $version = self::VERSION_2)
    {
        $this->providerKey = $providerKey;
        $this->secretCode = $secretCode;
        if (!in_array($version, [self::VERSION_1, self::VERSION_2], true)) {
            throw new RuntimeException(__METHOD__ . ' Invalid version');
        }
        $this->version = $version;
    }

    /**
     * @throws RuntimeException
     */
    public function getSubscribeLink(array $userDatas): string
    {
        $userDatas = $this->verifyAndOrder($userDatas);

        return $this->getAppUrl() . '/inscription?' .
            http_build_query(
                array_merge(
                    [
                        'providerKey' => $this->providerKey,
                    ],
                    $userDatas,
                    [
                        'checksum' => $this->generateCreateKey(
                            array_merge(
                                $userDatas,
                                ['secretCode' => $this->secretCode]
                            )
                        ),
                    ]
                )
            );
    }

    /**
     * @throws RuntimeException
     */
    private function verifyAndOrder(array $userDatas): array
    {
        $orderedUserDatas = [];
        foreach (self::DEFINITION_AND_ORDER[$this->version] as $item) {
            if (!isset($userDatas[$item])) {
                throw new RuntimeException(__METHOD__ . ' Missing mendatory field ' . $item);
            }

            $orderedUserDatas[$item] = $userDatas[$item];
        }

        return $orderedUserDatas;
    }

    public function setDemoMode(bool $demoMode)
    {
        $this->demoMode = $demoMode;
    }

    private function getAppUrl(): string
    {
        return $this->demoMode ? self::APP_URL_DEMO : self::APP_URL;
    }

    private function generateCreateKey(array $vars): string
    {
        $key = '';
        foreach ($vars as $var) {
            $key .= trim($var);
        }

        return md5($key);
    }
}
