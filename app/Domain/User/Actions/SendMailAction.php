<?php

namespace Domain\User\Actions;

use App\Services\MailService;
use Spatie\QueueableAction\QueueableAction;
use Domain\User\Repositories\UserRepository;
use Domain\User\Models\User;

class SendMailAction
{
    use QueueableAction;

    protected MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function execute(User $user) : void
    {
        $this->mailService->data(['name'=>'Fabio Rocha']);
        $this->mailService->template('docfacil_bemvindo');
        $this->mailService->tags(['docfacil_bemvindo']);
        $this->mailService->send(
            "Bem Vindo",
            "fabio@fabiofarias.com.br",
            "Docfacil <docfacil@docfacil.me>"
        );
    }
}
