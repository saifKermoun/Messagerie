<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessage;
use App\Notifications\MessageRecieved;
use App\Repository\ConversationRepository;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthManager;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ConversationsController extends Controller
{
    /**
     * @var ConversationRepository
     */
    private $conversations;
    /**
     * @var AuthManager
     */
    private $auth;

    public function __construct(ConversationRepository $conversations, AuthManager $auth) {

        $this->middleware('auth');
        $this->conversations = $conversations;
        $this->auth = $auth;
    }

    public function index () {
        return view('conversations/index', [
            'users' => $this->conversations->getConversations($this->auth->user()->id),
            'unread' => $this->conversations->unreadCount($this->auth->user()->id)
        ]);
    }


    public function show (User $user) {
        $me = $this->auth->user();
        $messages = $this->conversations->getMessagesFor($me->id, $user->id)->paginate(50);
        $unread = $this->conversations->unreadCount($me->id);
        if(isset($unread[$user->id])) {
            $this->conversations->readAllFrom($user->id, $me->id);
            unset($unread[$user->id]);
        }

        return view('conversations/show', [
            'users' => $this->conversations->getConversations($me->id),
            'user' => $user,
            'messages' => $messages,
            'unread' => $unread
        ]);
    }

    public function store (user $user, StoreMessage $request) {
        $message = $this->conversations->createMessage(
            $request->get('content'),
            $this->auth->user()->id,
            $user->id
        );

        $user->notify(new MessageRecieved($message));
        return redirect(route('conversations.show', ['id' => $user->id]));
    }
}
