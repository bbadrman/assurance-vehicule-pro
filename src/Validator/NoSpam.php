<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class NoSpam extends Constraint
{
    public const SPAM_URL = 'spam-url';
    public const SPAM_KEYWORD = 'spam-keyword';
    public const SPAM_DOMAIN = 'spam-domain';
    public const SPAM_PATTERN = 'spam-pattern';
    public const SPAM_EMOJI = 'spam-emoji';

    public array $spamKeywords = [
        'payment',
        'transfer',
        'balance',
        'sign in',
        'earn money',
        'bitcoin',
        'crypto',
        'cripton',
        'crypton',
        'wallet',
        'account verified',
        'dollar',
        'usdt',
        'usdc',
        'binance',
        'forex',
        'trading',
        'investissement',
        'gain quotidien',
        'profit',
        'return',
        'passive income',
    ];

    public array $suspiciousDomains = [
        'graph.org',
        't.me',
        'bit.ly',
        'tinyurl.com',
        'tinyurl',
    ];

    public array $spamEmojis = [
        '💳',
        '💵',
        '📊',
        '🔥',
        '💰',
        '🤑',
        '💸',
        '🏦',
        '📈',
        '🪙',
        '💎',
        '🧧',
        '💶',
        '💴',
        '💱',
    ];

    public string $message = 'Contenu non autorisé.';
    public string $messageUrl = 'Les liens et URLs ne sont pas autorisés.';
    public string $messageKeyword = 'Ce champ contient des mots interdits associés au spam ou aux arnaques.';
    public string $messageDomain = 'Ce domaine est interdit.';
    public string $messagePattern = 'Contenu suspect détecté.';
    public string $messageEmoji = 'Les emojis ne sont pas autorisés dans ce champ.';

    public string $mode = 'strict';
    public bool $alphaOnly = false;

    public function __construct(mixed $options = null, ?array $groups = null, mixed $payload = null)
    {
        if (is_array($options)) {
            $allowed = ['message', 'messageUrl', 'messageKeyword', 'messageDomain', 'messagePattern', 'messageEmoji', 'spamKeywords', 'suspiciousDomains', 'spamEmojis', 'mode', 'alphaOnly'];
            $options = array_intersect_key($options, array_flip($allowed));
        } elseif (is_string($options)) {
            $options = ['message' => $options];
        } else {
            $options = [];
        }

        parent::__construct($options, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}
