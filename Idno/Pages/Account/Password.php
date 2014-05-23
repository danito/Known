<?php

    /**
     * Recover a forgotten password
     */

    namespace Idno\Pages\Account {
        use Idno\Entities\User;

        /**
         * Default class to serve the password recovery page
         */
        class Password extends \Idno\Common\Page
        {

            function getContent() {

                $this->reverseGatekeeper();
                $t        = \Idno\Core\site()->template();

                if ($sent = $this->getInput('sent')) {
                    $t->body  = $t->draw('account/password/sent');
                    $t->title = 'Password recovery email sent';
                } else {
                    $t->body  = $t->draw('account/password');
                    $t->title = 'Recover password';
                }

                $t->drawPage();

            }

            function postContent() {

                $this->reverseGatekeeper();
                $email = $this->getInput('email');

                if ($user = User::getByEmail($email)) {

                    if ($auth_code = $user->addPasswordRecoveryCode()) {

                        $user->save();  // Save the recovery code to the user

                        // TODO: send email!
                        
                        $this->forward(\Idno\Core\site()->config()->getURL() . 'account/password/?sent=true');

                    }

                }
                \Idno\Core\site()->session()->addMessage("Oh no! We couldn't find an account associated with that email address.");
                $this->forward(\Idno\Core\site()->config()->getURL() . 'account/password');

            }

        }

    }