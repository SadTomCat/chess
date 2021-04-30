<?php


namespace App\Websockets;

use App\Exceptions\WebsocketManagerException;
use Pusher\ApiErrorException;
use Pusher\Pusher;
use Pusher\PusherException;

class PusherManager implements IWebsocketManager
{
    private Pusher $conn;

    public function __construct()
    {
        try {
            $connection = config('broadcasting.connections.pusher');

            $this->conn = new Pusher(
                $connection['key'],
                $connection['secret'],
                $connection['app_id'],
                [
                    'cluster' => $connection['options']['cluster'],
                ]
            );

        } catch (PusherException $e) {
            abort(500, 'What went wrong');
        }
    }

    /**
     * @param string $prefix
     * @return array
     * @throws WebsocketManagerException
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
     * @throws WebsocketManagerException
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
     * @throws WebsocketManagerException
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
     * @throws WebsocketManagerException
     */
    public function getChannelsInfo(string|array $channels): array
    {
        $channels = is_array($channels) ? $channels : [$channels];
        $channelsInfo = [];

        try {
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

        } catch (PusherException | ApiErrorException $e) {
            throw new WebsocketManagerException('Exception in pusher', 500);
        }

        return $channelsInfo;
    }

    /**
     * @param array|string $channels
     * @return array
     * @throws WebsocketManagerException
     */
    public function getUsers(string|array $channels): array
    {
        $channels = is_array($channels) ? $channels : [$channels];
        $userInfoByChannel = [];

        try {
            foreach ($channels as $channel) {
                if (str_starts_with($channel, 'presence-')) {
                    $info = (array)$this->conn->get_users_info($channel);
                    $formatInfo = $this->formatUsers($info);
                    $userInfoByChannel[$channel] = $formatInfo;
                }
            }

        } catch (ApiErrorException | PusherException $e) {
            throw new WebsocketManagerException('Exception in pusher', 500);
        }

        return $userInfoByChannel;
    }

    /**
     * @param string $type
     * @param string $prefix
     * @return array
     * @throws WebsocketManagerException
     */
    private function getChannelsByChannelType(string $type = '', string $prefix = ''): array
    {
        $type = ($type === 'presence' || $type === 'private') ? $type . '-' : '';

        try {
            $fullPrefix = $type . $prefix;
            $channels = (array)$this->conn->get_channels(['filter_by_prefix' => $fullPrefix]);

        } catch (PusherException | ApiErrorException $e) {
            throw new WebsocketManagerException('Exception in pusher', 500);
        }

        return $channels;
    }

    /**
     * @param array $channels
     * @param string $type
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

    private function formatUsers(array $users): array
    {
        $formatUsers = [];

        foreach ($users['users'] as $key => $val) {
            $formatUsers[] = $val->id;
        }

        return $formatUsers;
    }
}
