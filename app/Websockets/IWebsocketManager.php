<?php

namespace App\Websockets;

interface IWebsocketManager
{
    public function getAllChannels(string $prefix = ''): array;

    public function getAllPrivateChannels(string $prefix = ''): array;

    public function getAllPresenceChannels(string $prefix = ''): array;

    public function getChannelsInfo(string|array $channels): array;

    public function getUsers(string|array $channels): array;
}
