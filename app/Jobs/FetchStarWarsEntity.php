<?php

namespace App\Jobs;

use App\Notifications\FetchedStarWarsEntity;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Zttp\Zttp;
use Zttp\ZttpResponse;

class FetchStarWarsEntity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const API_URL = 'https://swapi.co';

    /**
     * ID of entity to fetch
     *
     * @var int
     */
    private $entityId;

    /**
     * Type of entity to fetch
     *
     * @var string
     */
    private $entityType;

    /**
     * User that receives the email notification
     *
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @param int $entityId
     * @param string $entityType
     * @param User $user
     */
    public function __construct(int $entityId, string $entityType, User $user)
    {
        $this->setEntityId($entityId);
        $this->setEntityType($entityType);
        $this->setUser($user);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $headers = ['Content-Type' => 'application/json'];
        $url = sprintf(
            '%1$s/api/%2$s/%3$s',
            self::API_URL, $this->getEntityType(),
            $this->getEntityId()
        );

        /**
         * @var ZttpResponse $response
         */
        $response = Zttp::withHeaders($headers)->get($url);

        if (empty($response) === true || 200 !== $response->status()) {
            $error = 'Failed to fetch Star Wars entity.';

            \Log::error($error, [
                'url' => $url,
                'response' => $response->json(),
                'status' => $response->status(),
                'headers' => $response->headers(),
            ]);

            throw new \RuntimeException($error);
        }

        $this->getUser()->notify(new FetchedStarWarsEntity($response->json()));
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->entityId;
    }

    /**
     * @param int $entityId
     */
    public function setEntityId(int $entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * @param string $entityType
     */
    public function setEntityType(string $entityType)
    {
        $this->entityType = $entityType;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
