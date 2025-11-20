<?php

namespace Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use App\Enum\ResponseMessage\SuccessMessageEnum;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


/**
 * @todo faire test sur la modification d'un timesheet qui n'est pas le notre,
 */
class TimesheetApiTest extends ApiTestCase
{
    private static Client $client;
    private static UserInterface $user;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient(['cookie' => true]);
        $userRepository = self::getContainer()->get(UserRepository::class);
        self::$user = $userRepository->findOneBy(['email' => 'user@example.com']);
        self::$client->loginUser(self::$user);
        static::$alwaysBootKernel = true;
    }

    private function defaultTimesheetPayload(array $overrides = []): array
    {
        $payload = [
            'employee_id' => '',
            'project_id' => '',
            'start_period' => '',
            'end_period' => '',
            'comment' => '',
            'days' => [
                'sunday' => [
                    'project_time' => 0,
                    'is_min_daily_rest_met' => null,
                    'is_work_shift_valid' => null,
                    'worked_more_than_half_day' => null,
                    'lunch_break' => null,
                    'location' => null,
                    'comment' => '',
                ],
                'monday' => [
                    'project_time' => 7.40,
                    'is_min_daily_rest_met' => true,
                    'is_work_shift_valid' => true,
                    'worked_more_than_half_day' => true,
                    'lunch_break' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
                'tuesday' => [
                    'project_time' => 7.40,
                    'is_min_daily_rest_met' => true,
                    'is_work_shift_valid' => true,
                    'worked_more_than_half_day' => true,
                    'lunch_break' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
                'wednesday' => [
                    'project_time' => 7.40,
                    'is_min_daily_rest_met' => true,
                    'is_work_shift_valid' => true,
                    'worked_more_than_half_day' => true,
                    'lunch_break' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
                'thursday' => [
                    'project_time' => 7.40,
                    'is_min_daily_rest_met' => true,
                    'is_work_shift_valid' => true,
                    'worked_more_than_half_day' => true,
                    'lunch_break' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
                'friday' => [
                    'project_time' => 7.40,
                    'is_min_daily_rest_met' => true,
                    'is_work_shift_valid' => true,
                    'worked_more_than_half_day' => true,
                    'lunch_break' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
            'saturday' => [
                'project_time' => 0,
                'is_min_daily_rest_met' => null,
                'is_work_shift_valid' => null,
                'worked_more_than_half_day' => null,
                'lunch_break' => null,
                'location' => null,
                'comment' => '',
            ],
        ];

        return array_merge_recursive($payload, $overrides);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Créé une feuille de temps avec toutes les informations valides
     */
    public function testCreateTimesheet(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => $this->defaultTimesheetPayload()]
        );

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            'message' => SuccessMessageEnum::TS_SUBMITTED,
            'prevent_editing' => true,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Créé une feuille de temps pour un employée qui n'est pas nous même ou notre subordonné
     */
    public function testCreateTimesheetNotAuthorized(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
                'json' => $this->defaultTimesheetPayload(['employee_id' => '',])]
        );

        self::assertResponseStatusCodeSame(403);
        self::assertJsonContains([
            'error' => [
                "message" => "Access Denied.",
                "code" => 403
            ]
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le total doit être de 37 heures, là on enlève 7,40 heures pour le jour du lundi
     */
    public function testCreateTimesheetNotEnoughTotalHours(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => [
                $this->defaultTimesheetPayload([
                    'monday' => [
                        'project_time' => 0,
                        'is_min_daily_rest_met' => true,
                        'is_work_shift_valid' => true,
                        'worked_more_than_half_day' => true,
                        'lunch_break' => 1,
                        'location' => [
                            'am' => '',
                            'pm' => '',
                        ],
                        'comment' => '',
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_NOT_ENOUGH_TOTAL_HOURS_->value,
            'violations' => [
                [
                    'property_path' => 'monday.project_time',
                ],
            ],
            'prevent_editing' => false,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le total doit être de 37 heures, là on ajoute 1 heure pour le jour du lundi
     */
    public function testCreateTimesheetTooMuchTotalHours(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => [
                $this->defaultTimesheetPayload([
                    'monday' => [
                        'project_time' => 8.40,
                        'is_min_daily_rest_met' => true,
                        'is_work_shift_valid' => true,
                        'worked_more_than_half_day' => true,
                        'lunch_break' => 1,
                        'location' => [
                            'am' => '',
                            'pm' => '',
                        ],
                        'comment' => '',
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_TOO_MUCH_TOTAL_HOURS_->value,
            'violations' => [
                [
                    'property_path' => 'monday.project_time',
                ],
            ],
            'prevent_editing' => false,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du lundi est travaillé mais le champ du temps de repos minimum pour le même jour n'est pas rempli
     */
    public function testCreateTimesheetMinimumRestIncoherent(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => [
                $this->defaultTimesheetPayload([
                    'monday' => [
                        'project_time' => 7.40,
                        'is_min_daily_rest_met' => null,
                        'is_work_shift_valid' => true,
                        'worked_more_than_half_day' => true,
                        'lunch_break' => 1,
                        'location' => [
                            'am' => '',
                            'pm' => '',
                        ],
                        'comment' => '',
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_MINIMUM_DAILY_REST_MISSING->value,
            'violations' => [
                [
                    'property_path' => 'monday.is_min_daily_rest_met',
                ],
            ],
            'prevent_editing' => false,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du lundi est travaillé mais le champ pour le début et la fin du temps de travail pour le même jour n'est pas rempli
     */
    public function testCreateTimesheetWorkShiftIncoherent(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => [
                $this->defaultTimesheetPayload([
                    'monday' => [
                        'project_time' => 7.40,
                        'is_min_daily_rest_met' => true,
                        'is_work_shift_valid' => null,
                        'worked_more_than_half_day' => true,
                        'lunch_break' => 1,
                        'location' => [
                            'am' => '',
                            'pm' => '',
                        ],
                        'comment' => '',
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_WORK_SHIFT_MISSING->value,
            'violations' => [
                [
                    'property_path' => 'monday.is_work_shift_valid',
                ],
            ],
            'prevent_editing' => false,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du lundi est travaillé mais le champ pour indiquer un travail supérieur à une demi journée
     * pour le même jour n'est pas rempli
     */
    public function testCreateTimesheetWorkedMoreThanHalfDayIncoherent(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => [
                $this->defaultTimesheetPayload([
                    'monday' => [
                        'project_time' => 7.40,
                        'is_min_daily_rest_met' => true,
                        'is_work_shift_valid' => true,
                        'worked_more_than_half_day' => null,
                        'lunch_break' => 1,
                        'location' => [
                            'am' => '',
                            'pm' => '',
                        ],
                        'comment' => '',
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_WORKED_MORE_THAN_HALF_DAY_MISSING->value,
            'violations' => [
                [
                    'property_path' => 'monday.worked_more_than_half_day',
                ],
            ],
            'prevent_editing' => false,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du lundi est travaillé mais le champ pour indiquer la pose du midi pour le même jour n'est pas rempli
     */
    public function testCreateTimesheetLunchBreakIncoherent(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => [
                $this->defaultTimesheetPayload([
                    'monday' => [
                        'project_time' => 7.40,
                        'is_min_daily_rest_met' => true,
                        'is_work_shift_valid' => true,
                        'worked_more_than_half_day' => true,
                        'lunch_break' => null,
                        'location' => [
                            'am' => '',
                            'pm' => '',
                        ],
                        'comment' => '',
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_LUNCH_BREAK_MISSING->value,
            'violations' => [
                [
                    'property_path' => 'monday.lunch_break',
                ],
            ],
            'prevent_editing' => false,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du lundi est travaillé mais le champ pour indiquer localisation du même jour n'est pas rempli
     */
    public function testCreateTimesheetLocationIncoherent(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => [
                $this->defaultTimesheetPayload([
                    'monday' => [
                        'project_time' => 7.40,
                        'is_min_daily_rest_met' => true,
                        'is_work_shift_valid' => true,
                        'worked_more_than_half_day' => true,
                        'lunch_break' => 1,
                        'location' => null,
                        'comment' => '',
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_LOCATION_MISSING->value,
            'violations' => [
                [
                    'property_path' => 'location.monday',
                ],
            ],
            'prevent_editing' => false,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du dimanche n'est pas travaillé mais le champ pour indiquer la localisation du même jour est rempli
     */
    public function testCreateTimesheetLocationIncoherentWithNotWorkedDay(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => [
                $this->defaultTimesheetPayload([
                    'sunday' => [
                        'project_time' => 0,
                        'is_min_daily_rest_met' => null,
                        'is_work_shift_valid' => null,
                        'worked_more_than_half_day' => null,
                        'lunch_break' => null,
                        'location' => [
                            'am' => '',
                            'pm' => '',
                        ],
                        'comment' => '',
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_LOCATION_UNEXPECTED_VALUE->value,
            'violations' => [
                [
                    'property_path' => 'sunday.location.am',
                ],
                [
                    'property_path' => 'sunday.location.pm',
                ],
            ],
            'prevent_editing' => false,
        ]);
    }
}
