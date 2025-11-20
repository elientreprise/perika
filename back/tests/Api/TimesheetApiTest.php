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
            'project_times' => [
                'sunday' => 0,
                'monday' => 0,
                'tuesday' => 0,
                'wednesday' => 0,
                'thursday' => 0,
                'friday' => 0,
                'saturday' => 0,
            ],
            'rest' => [
                'is_min_daily_rest_met' => [
                    'sunday' => null, 'monday' => true, 'tuesday' => true,
                    'wednesday' => true, 'thursday' => true, 'friday' => true, 'saturday' => null,
                ],
                'is_work_shift_valid' => [
                    'sunday' => null, 'monday' => true, 'tuesday' => true,
                    'wednesday' => true, 'thursday' => true, 'friday' => true, 'saturday' => null,
                ],
                'worked_more_than_half_day' => [
                    'sunday' => null, 'monday' => true, 'tuesday' => true,
                    'wednesday' => true, 'thursday' => true, 'friday' => true, 'saturday' => null,
                ],
                'lunch_break' => [
                    'sunday' => null, 'monday' => 1, 'tuesday' => 1, 'wednesday' => 1,
                    'thursday' => 1, 'friday' => 1, 'saturday' => null,
                ],
                'comment' => '',
            ],
            'location' => [
                'sunday' => null,
                'monday' => ['am' => '', 'pm' => ''],
                'tuesday' => ['am' => '', 'pm' => ''],
                'wednesday' => ['am' => '', 'pm' => ''],
                'thursday' => ['am' => '', 'pm' => ''],
                'friday' => ['am' => '', 'pm' => ''],
                'saturday' => null,
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
            'json' => $this->defaultTimesheetPayload([
                'project_times' => [
                    'sunday' => 0,
                    'monday' => 7.40,
                    'tuesday' => 7.40,
                    'wednesday' => 7.40,
                    'thursday' => 7.40,
                    'friday' => 7.40,
                    'saturday' => 0,
                ],
            ]),
        ]);

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
     * Le total doit être de 37 heures, là on enlève 7,40 heures pour le jour du lundi
     */
    public function testCreateTimesheetNotEnoughTotalHours(): void
    {
        $response = self::$client->request('POST', '/api/timesheet', [
            'json' => [
                $this->defaultTimesheetPayload([
                    'project_times' => [
                        'sunday' => 0,
                        'monday' => 0,
                        'tuesday' => 7.40,
                        'wednesday' => 7.40,
                        'thursday' => 7.40,
                        'friday' => 7.40,
                        'saturday' => 0,
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_NOT_ENOUGH_TOTAL_HOURS_->value,
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
                    'project_times' => [
                        'sunday' => 0,
                        'monday' => 8.40,
                        'tuesday' => 7.40,
                        'wednesday' => 7.40,
                        'thursday' => 7.40,
                        'friday' => 7.40,
                        'saturday' => 0,
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_TOO_MUCH_TOTAL_HOURS_->value,
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
                    'employee_id' => '',
                    'project_id' => '',
                    'start_period' => '',
                    'end_period' => '',
                    'rest' => [
                        'is_min_daily_rest_met' => [
                            'monday' => null,
                        ],
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_MINIMUM_DAILY_REST_MISSING->value,
            'violations' => [
                [
                    'property_path' => 'rest.is_min_daily_rest_met.monday',
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
                    'employee_id' => '',
                    'project_id' => '',
                    'start_period' => '',
                    'end_period' => '',
                    'rest' => [
                        'is_work_shift_valid' => [
                            'monday' => null,
                        ],
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_WORK_SHIFT_MISSING->value,
            'violations' => [
                [
                    'property_path' => 'rest.is_work_shift_valid.monday',
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
                    'employee_id' => '',
                    'project_id' => '',
                    'start_period' => '',
                    'end_period' => '',
                    'rest' => [
                        'worked_more_than_half_day' => [
                            'monday' => null,
                        ],
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_WORKED_MORE_THAN_HALF_DAY_MISSING->value,
            'violations' => [
                [
                    'property_path' => 'rest.worked_more_than_half_day.monday',
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
                    'employee_id' => '',
                    'project_id' => '',
                    'start_period' => '',
                    'end_period' => '',
                    'rest' => [
                        'lunch_break' => [
                            'monday' => null,
                        ],
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_LUNCH_BREAK_MISSING->value,
            'violations' => [
                [
                    'property_path' => 'rest.lunch_break.monday',
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
                    'employee_id' => '',
                    'project_id' => '',
                    'start_period' => '',
                    'end_period' => '',
                    'location' => [
                        'monday' => null,
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
                    'employee_id' => '',
                    'project_id' => '',
                    'start_period' => '',
                    'end_period' => '',
                    'location' => [
                        'sunday' => ['am' => '', 'pm' => ''],
                    ],
                ]),
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'message' => ErrorMessageEnum::TS_LOCATION_UNEXPECTED_VALUE->value,
            'violations' => [
                [
                    'property_path' => 'location.sunday.am',
                ],
                [
                    'property_path' => 'location.sunday.pm',
                ],
            ],
            'prevent_editing' => false,
        ]);
    }
}
