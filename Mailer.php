<?php

namespace dimple\administrator;

use dimple\administrator\models\User;
use yii\base\Component;
use Yii;

class Mailer extends Component
{
    /** @var string */
    public $viewPath = '@dimple/administrator/views/mail';

    /** @var string|array Default: `\Yii::$app->params['adminEmail']` OR `no-reply@example.com` */
    public $sender;

    /** @var string */
    public $welcomeSubject;

    /** @var string */
    public $confirmationSubject;

    /** @var string */
    public $reconfirmationSubject;

    /** @var string */
    public $recoverySubject;

    /**
     * Sends an email to a user with credentials and confirmation link.
     *
     * @param  User  $user
     * @return bool
     */
    public function sendWelcomeMessage(User $user)
    {
        return $this->sendMessage($user->email,
            $this->welcomeSubject,
            'welcome',
            ['user' => $user]
        );
    }

    /**
     * Sends an email to a user with confirmation link.
     *
     * @param  User  $user
     * @return bool
     */
    public function sendConfirmationMessage(User $user)
    {
        return $this->sendMessage($user->email,
            $this->confirmationSubject,
            'confirmation',
            ['user' => $user]
        );
    }

    /**
     * Sends an email to a user with reconfirmation link.
     *
     * @param  User  $user
     * @return bool
     */
    public function sendReconfirmationMessage(User $user)
    {
        if ($token->type == Token::TYPE_CONFIRM_NEW_EMAIL) {
            $email = $user->unconfirmed_email;
        } else {
            $email = $user->email;
        }
        return $this->sendMessage($email,
            $this->reconfirmationSubject,
            'reconfirmation',
            ['user' => $user]
        );
    }

    /**
     * Sends an email to a user with recovery link.
     *
     * @param  User  $user
     * @return bool
     */
    public function sendRecoveryMessage(User $user)
    {
        return $this->sendMessage($user->email,
            $this->recoverySubject,
            'passwordReset',
            ['user' => $user]
        );
    }

    /**
     * @param  string $to
     * @param  string $subject
     * @param  string $view
     * @param  array  $params
     * @return bool
     */
    protected function sendMessage($to, $subject, $view, $params = [])
    {
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->viewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;

        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'no-reply@example.com';
        }

        return $mailer->compose(['html' => $view.'-html', 'text' =>$view.'-text'], $params)
            ->setTo($to)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
    }
}