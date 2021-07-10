<?php


namespace App\Websockets;

use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;

class PusherManager implements IWebsocketManager
{
    private Pusher $conn;

    /**
     * @throws PusherException
     */
    public function __construct()
    {
        $connection = config('broadcasting.connections.pusher');

        $this->conn = new Pusher(
            $connection['key'],
            $connection['secret'],
            $connection['app_id'],
            [
                'cluster' => $connection['options']['cluster'],
            ]
        );
    }

    /**
     * @param string $prefix
     * @return array
     * @throws PusherException
     */
    public function getAllChannels(string $prefix = ''): array
    {
        $channels = $this->getChannelsByChannelType('', $prefix);
        $formatChannels = $this->formatChannels($channels);

        return $this->parseChannelByType($formatChannels);
    }

    /**
     * @param string $prefix
     * @return array
     * @throws PusherException
     */
    public function getAllPrivateChannels(string $prefix = ''): array
    {
        $channels = $this->getChannelsByChannelType('private', $prefix);
        $formatChannels = $this->formatChannels($channels);

        return ['private' => $formatChannels];
    }

    /**
     * @param string $prefix
     * @return array
     * @throws PusherException
     */
    public function getAllPresenceChannels(string $prefix = ''): array
    {
        $channels = $this->getChannelsByChannelType('presence', $prefix);
        $formatChannels = $this->formatChannels($channels);

        return ['presence' => $formatChannels];
    }

    /**
     * @param array|string $channels
     * @return array
     * @throws PusherException
     */
    public function getChannelsInfo(string|array $channels): array
    {
        $channels = is_array($channels) ? $channels : [$channels];
        $channelsInfo = [];

        foreach ($channels as $channel) {
            $parameters = ['subscription_count'];

            if (str_starts_with($channel, 'presence-')) {
                $parameters[] = 'user_count';
            }

            $info = (array)$this->conn->get_channel_info($channel, [
                'info' => $parameters
            ]);

            $channelsInfo[$channel] = $info;
        }

        return $channelsInfo;
    }

    /**
     * @param array|string $channels
     * @return array
     * @throws PusherException
     */
    public function getUsers(string|array $channels): array
    {
        $channels = is_array($channels) ? $channels : [$channels];
        $userInfoByChannel = [];

        foreach ($channels as $channel) {
            if (str_starts_with($channel, 'presence-')) {
                $info = (array)$this->conn->get_users_info($channel);
                $formatInfo = $this->formatUsers($info);
                $userInfoByChannel[$channel] = $formatInfo;
            }
        }

        return $userInfoByChannel;
    }

    /**
     * @param string $type
     * @param string $prefix
     * @return array
     * @throws PusherException
     */
    private function getChannelsByChannelType(string $type = '', string $prefix = ''): array
    {
        $type = ($type === 'presence' || $type === 'private') ? $type . '-' : '';

        $fullPrefix = $type . $prefix;

        return (array)$this->conn->get_channels(['filter_by_prefix' => $fullPrefix]);
    }

    /**
     * @param array $channels
     * @return array
     */
    private function formatChannels(array $channels): array
    {
        $formatChannels = [];

        foreach ($channels['channels'] as $key => $val) {
            $formatChannels[] = $key;
        }

        return $formatChannels;
    }

    /**
     * @param array $channels
     * @return array
     */
    private function parseChannelByType(array $channels): array
    {
        $result = [];

        foreach ($channels as $val) {
            if (str_starts_with($val, 'presence-')) {
                $result['presence'][] = $val;
            } else if (str_starts_with($val, 'private-')) {
                $result['private'][] = $val;
            } else {
                $result['public'][] = $val;
            }
        }

        return $result;
    }

    /**
     * @param array $users
     * @return array
     */
    private function formatUsers(array $users): array
    {
        $formatUsers = [];

        foreach ($users['users'] as $key => $val) {
            $formatUsers[] = $val->id;
        }

        return $formatUsers;
    }
}
