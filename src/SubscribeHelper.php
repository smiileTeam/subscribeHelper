<?php

namespace Smiile\Api;

class SubscribeHelper
{
    const APP_URL = "https://app.smiile.com";

    /** @var string */
    private $providerKey;
    /** @var string */
    private $secretCode;

    private $definitionAndOrder = array(
        "address",
        "civility",
        "name",
        "lastName",
        "mail",
    );

    /**
     * @param string $providerKey
     * @param string $secretCode
     */
    public function __construct(string $providerKey, string $secretCode)
    {
        $this->providerKey = $providerKey;
        $this->secretCode = $secretCode;
    }

    /**
     * @param array $userDatas
     * @return string
     * @throws \Exception
     */
    public function getSubscribeLink(array $userDatas)
    {
        $userDatas = $this->verifyAndOrder($userDatas);

        return self::APP_URL . '/inscription?' .
            http_build_query(
                array_merge(
                    array(
                        "providerKey" => $this->providerKey,
                    ),
                    $userDatas,
                    array(
                        "checksum" => $this->generateCreateKey(
                            array_merge(
                                $userDatas,
                                array("secretCode" => $this->secretCode)
                            )
                        ),
                    )
                )
            );
    }

    /**
     * @param array $userDatas
     * @return array
     * @throws \Exception
     */
    protected function verifyAndOrder(array $userDatas)
    {
        $orderedUserDatas = [];
        foreach ($this->definitionAndOrder as $item) {
            if (!isset($userDatas[$item])) {
                throw new \Exception(__METHOD__ . " Missing mendatory field " . $item);
            } else {
                $orderedUserDatas[$item] = $userDatas[$item];
            }
        }

        return $orderedUserDatas;
    }

    /**
     * @param array $vars
     * @return string
     */
    private function generateCreateKey(array $vars)
    {
        $key = '';
        foreach ($vars as $var) {
            $key .= trim($var);
        }

        return md5($key);
    }
}
