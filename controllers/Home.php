<?php

namespace modxnl\Controllers;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use DMS\Service\Meetup\MeetupKeyAuthClient;
use GuzzleHttp\Client;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Slim\Http\Request;
use Slim\Http\Response;

class Home extends Html
{
    public function initialize(Request $request, Response $response, array $args = array())
    {
        if (parent::initialize($request, $response, $args)) {
            $this->getMeetupEvents();
            $this->getMODXNewsFeed();
            return true;
        }
        return false;
    }

    public function getMeetupEvents()
    {
        // Get a local cache adapter
        $fsAdapter = new Local($_ENV['CACHE_DIR'] . 'meetup.com/');
        $fs = new Filesystem($fsAdapter);
        $cachePool = new FilesystemCachePool($fs);

        // Try to load the data from cache, if possible
        $item = $cachePool->getItem('events');
        if ($item->isHit()) {
            $data = $item->get();
            $this->setVariable('future_events', $data['future']);
            $this->setVariable('past_events', $data['past']);
            return;
        }

        // Create a client instance with our api key
        $client = MeetupKeyAuthClient::factory(array('key' => $_ENV['MEETUP_KEY']));

        // Get future events
        $response = $client->getEvents([
            'group_urlname' => $_ENV['MEETUP_GROUP_PATH'],
            'status' => 'upcoming'
        ]);
        $future = [];
        foreach ($response as $event) {
            $e = $event;
            $e['time'] = $e['time'] / 1000;
            $future[] = $e;
        }

        // Past events
        $response = $client->getEvents([
            'group_urlname' => $_ENV['MEETUP_GROUP_PATH'],
            'status' => 'past',
            'desc' => 'desc',
        ]);
        $past = [];
        foreach ($response as $event) {
            $e = $event;
            $e['time'] = $e['time'] / 1000;
            $past[] = $e;
        }

        $this->setVariable('future_events', $future);
        $this->setVariable('past_events', $past);

        // Store data in the cache so we don't have to hit up the API all the time
        $item->set([
            'future' => $future,
            'past' => $past,
        ]);
        $item->expiresAfter(6 * 60 * 60); // 6 hours cache
        $cachePool->save($item);
    }

    public function getMODXNewsFeed()
    {
        // Get a local cache adapter
        $fsAdapter = new Local($_ENV['CACHE_DIR'] . 'modx.com/');
        $fs = new Filesystem($fsAdapter);
        $cachePool = new FilesystemCachePool($fs);

        // Try to load the data from cache, if possible
        $item = $cachePool->getItem('newsfeed');
        if ($item->isHit()) {
            $data = $item->get();
            $this->setVariable('news_feed', $data);
            return;
        }


        $client = new Client();

        $response = $client->request('GET', 'https://modx.com/feeds/modx-cms-blogs.rss');

        if ($response->getStatusCode() === 200) {
            $data = [];

            $xml = simplexml_load_string($response->getBody());

            foreach ($xml->channel->item as $feedItem) {
                // Clean up the description a bit so we can add our own stuff to it
                $description = strip_tags((string)$feedItem->description);
                $description = str_replace('Read the complete post at MODX.com.', '', $description);
                $data[] = [
                    'title' => (string)$feedItem->title,
                    'link' => (string)$feedItem->link,
                    'description' => $description,
                    'pubDate' => (string)$feedItem->pubDate,
                ];
            }

            $this->setVariable('news_feed', $data);

            $item->set($data);
            $item->expiresAfter(24 * 60 * 60);
            $cachePool->save($item);
        }

    }
}