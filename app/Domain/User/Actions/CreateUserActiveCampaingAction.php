<?php

namespace Domain\User\Actions;

use Spatie\QueueableAction\QueueableAction;
use Domain\User\Repositories\UserRepository;
use GuzzleHttp\Client;
use Domain\User\Models\User;

class CreateUserActiveCampaingAction
{
    use QueueableAction;

    protected Client $httpClient;
    protected $baseUri;
    protected UserRepository $userRepository;

    /**
     * Create a new action instance.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->baseUri = env('ACTIVE_CAMPAING_URL');
        $this->httpClient = new Client([
            'headers' => [
                'Api-Token' => env('ACTIVE_CAMPAING_KEY'),
            ],
            'allow_redirects' => false
        ]);
    }

    /**
     * Execute the action.
     *
     * @param User $user
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(User $user)
    {
        $data = [];

        if (is_null($user->active_campaign_id)) {
            $response = $this->httpClient->request('POST',$this->baseUri.'/contacts',[
                'json' => [
                    'contact' => [
                        'email' => $user->email,
                        'first_name' => $user->name,
                        'phone' => $user->phone,
                        'status' => 1
                    ]
                ]
            ]);
            if ($response->getStatusCode() === 201) {
                $data = json_decode($response->getBody()->getContents(), true);
                $this->userRepository->update(['active_campaign_id'=> $data['contact']['id']], $user->id);
            }
        }

        return $data;
    }
}
