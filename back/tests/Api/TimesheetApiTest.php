<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Enum\Entity\WeekDayEnum;
use App\Enum\ResponseMessage\ErrorMessageEnum;
use App\Enum\ResponseMessage\SuccessMessageEnum;
use App\Factory\TimesheetFactory;
use App\Factory\UserFactory;
use App\Story\DefaultUseCaseStory;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class TimesheetApiTest extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    private static Client $client;
    private static UserInterface $user;

    protected function setUp(): void
    {
        self::$alwaysBootKernel = true;
        parent::setUp();
        DefaultUseCaseStory::load();

        static::$client = static::createClient([], [
            'headers' => ['Content-Type' => 'application/ld+json'],
        ]);

        self::$user = UserFactory::find(['email' => 'employee@example.com']);
        static::$client->loginUser(self::$user);
    }

    private function defaultTimesheetPayload(array $overrides = []): array
    {
        $employeeIri = $this->findIriBy(User::class, ['email' => self::$user->getEmail()]);

        $payload = [
            'employee' => $employeeIri,
            'endPeriod' => (new \DateTimeImmutable())->format('Y-m-d'),
            'comment' => '',
            'workDays' => [
                WeekDayEnum::Sunday->value => [
                    'day' => WeekDayEnum::Sunday->value,
                    'projectTime' => 0,
                    'isMinDailyRestMet' => null,
                    'isWorkShiftValid' => null,
                    'workedMoreThanHalfDay' => null,
                    'lunchBreak' => null,
                    'location' => null,
                    'comment' => '',
                ],
                WeekDayEnum::Monday->value => [
                    'day' => WeekDayEnum::Monday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
                WeekDayEnum::Tuesday->value => [
                    'day' => WeekDayEnum::Tuesday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
                WeekDayEnum::Wednesday->value => [
                    'day' => WeekDayEnum::Wednesday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
                WeekDayEnum::Thursday->value => [
                    'day' => WeekDayEnum::Thursday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
                WeekDayEnum::Friday->value => [
                    'day' => WeekDayEnum::Friday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
            WeekDayEnum::Saturday->value => [
                'day' => WeekDayEnum::Saturday->value,
                'projectTime' => 0,
                'isMinDailyRestMet' => null,
                'isWorkShiftValid' => null,
                'workedMoreThanHalfDay' => null,
                'lunchBreak' => null,
                'location' => null,
                'comment' => '',
            ],
        ];

        return array_replace_recursive($payload, $overrides);
    }

    private function postTimesheet(array $payload)
    {
        return self::$client->request('POST', '/api/timesheets', [
            'json' => $payload,
        ]);
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

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2025-11-21',
        ]));

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            'message' => SuccessMessageEnum::TS_SUBMITTED->value,
            'preventEditing' => true,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Check si une feuille de temps est déjà créée à la période donnée
     */
    public function testCheckTimesheetExist(): void
    {
        $timesheet = TimesheetFactory::new([
            'employee' => self::$user,
        ])->withWorkDays()->create();

        $employeeIri = $this->findIriBy(User::class, ['email' => $timesheet->getEmployee()->getEmail()]);

        $response = self::$client->request('POST', '/api/timesheets/check-exists', [
            'json' => [
                'date' => (new \DateTimeImmutable())->format('Y-m-d'),
                'employee' => $employeeIri,
            ]]
        );

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            'exists' => true,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Créé une feuille de temps avec toutes les informations valides pour un subordonné
     */
    public function testCreateTimesheetForSubordinate(): void
    {
        $employee = UserFactory::createOne([
            'manager' => self::$user,
        ]);
        $employeeIri = $this->findIriBy(User::class, ['uuid' => $employee->getUuid()]);

        $response = $this->postTimesheet($this->defaultTimesheetPayload(['employee' => $employeeIri]));

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            'message' => SuccessMessageEnum::TS_SUBMITTED->value,
            'preventEditing' => true,
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Créé une feuille de temps avec toutes les informations valides pour un employée qui n'hésiste pas
     */
    public function testCreateTimesheetForUndefinedEmployee(): void
    {
        $employee = UserFactory::createOne();
        $employeeIri = $this->findIriBy(User::class, ['uuid' => $employee->getUuid()]);
        UserFactory::truncate();

        $response = $this->postTimesheet($this->defaultTimesheetPayload(['employee' => $employeeIri]));


        self::assertResponseStatusCodeSame(400);
        self::assertJsonContains([
            'description' => "Item not found for \"$employeeIri\".",
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Impossible de créer une feuille de temps pour un employée qui n'est pas nous même ou notre subordonné
     */
    public function testCreateTimesheetNotAuthorized(): void
    {
        $employee = UserFactory::createOne();
        $employeeIri = $this->findIriBy(User::class, ['uuid' => $employee->getUuid()]);

        $response = $this->postTimesheet($this->defaultTimesheetPayload(['employee' => $employeeIri]));


        self::assertResponseStatusCodeSame(403);
        self::assertJsonContains([
            'detail' => ErrorMessageEnum::TS_CREATION_NOT_AUTHORIZED->value,
            'status' => 403,
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
        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Monday->value => [
                    'day' => WeekDayEnum::Monday->value,
                    'projectTime' => 0,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'workDays',
                    'message' => ErrorMessageEnum::TS_NOT_ENOUGH_TOTAL_HOURS->value,
                ],
            ],
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

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Monday->value => [
                    'day' => WeekDayEnum::Monday->value,
                    'projectTime' => 8.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'workDays',
                    'message' => ErrorMessageEnum::TS_TOO_MUCH_TOTAL_HOURS->value,
                ],
            ],
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
    public function testCreateTimesheetMinimumRestIncoherentWithWorkedDay(): void
    {

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Monday->value => [
                    'day' => WeekDayEnum::Monday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => null,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'message' => ErrorMessageEnum::TS_MINIMUM_DAILY_REST_MISSING->value,
                    'propertyPath' => 'workDays[1].isMinDailyRestMet',
                ],
            ],
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du Dimanche n'est pas travaillé mais le champ du temps de repos minimum pour le même jour est rempli
     */
    public function testCreateTimesheetMinimumRestIncoherentWithNotWorkedDay(): void
    {

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Sunday->value => [
                    'day' => WeekDayEnum::Sunday->value,
                    'projectTime' => 0,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => null,
                    'workedMoreThanHalfDay' => null,
                    'lunchBreak' => null,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'message' => ErrorMessageEnum::TS_MINIMUM_DAILY_REST_UNEXPECTED_VALUE->value,
                    'propertyPath' => 'workDays[0].isMinDailyRestMet',
                ],
            ],
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
    public function testCreateTimesheetWorkShiftIncoherentWithWorkedDay(): void
    {

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Monday->value => [
                    'day' => WeekDayEnum::Monday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => null,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'message' => ErrorMessageEnum::TS_WORK_SHIFT_MISSING->value,
                    'propertyPath' => 'workDays[1].isWorkShiftValid',
                ],
            ],
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du Dimanche n'est pas travaillé mais le champ pour le début et la fin du temps de travail pour le même jour est rempli
     */
    public function testCreateTimesheetWorkShiftIncoherentWithNotWorkedDay(): void
    {

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Sunday->value => [
                    'day' => WeekDayEnum::Sunday->value,
                    'projectTime' => 0,
                    'isMinDailyRestMet' => null,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => null,
                    'lunchBreak' => null,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'message' => ErrorMessageEnum::TS_WORK_SHIFT_UNEXPECTED_VALUE->value,
                    'propertyPath' => 'workDays[0].isWorkShiftValid',
                ],
            ],
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
    public function testCreateTimesheetWorkedMoreThanHalfDayIncoherentWithWorkedDay(): void
    {
        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Monday->value => [
                    'day' => WeekDayEnum::Monday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => null,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'message' => ErrorMessageEnum::TS_WORKED_MORE_THAN_HALF_DAY_MISSING->value,
                    'propertyPath' => 'workDays[1].workedMoreThanHalfDay',
                ],
            ],
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du Dimanche n'est pas travaillé mais le champ pour indiquer un travail supérieur à une demi journée
     * pour le même jour est rempli
     */
    public function testCreateTimesheetWorkedMoreThanHalfDayIncoherentWithNotWorkedDay(): void
    {

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Sunday->value => [
                    'day' => WeekDayEnum::Sunday->value,
                    'projectTime' => 0,
                    'isMinDailyRestMet' => null,
                    'isWorkShiftValid' => null,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => null,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'message' => ErrorMessageEnum::TS_WORKED_MORE_THAN_HALF_DAY_UNEXPECTED_VALUE->value,
                    'propertyPath' => 'workDays[0].workedMoreThanHalfDay',
                ],
            ],
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
    public function testCreateTimesheetLunchBreakIncoherentWithWorkedDay(): void
    {

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Monday->value => [
                    'day' => WeekDayEnum::Monday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => null,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'message' => ErrorMessageEnum::TS_LUNCH_BREAK_MISSING->value,
                    'propertyPath' => 'workDays[1].lunchBreak',
                ],
            ],
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     *
     * Le jour du dimanche n'est pas travaillé mais le champ pour indiquer la pose du midi pour le même jour est rempli
     */
    public function testCreateTimesheetLunchBreakIncoherentWithNotWorkedDay(): void
    {
        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Sunday->value => [
                    'day' => WeekDayEnum::Sunday->value,
                    'projectTime' => 0,
                    'isMinDailyRestMet' => null,
                    'isWorkShiftValid' => null,
                    'workedMoreThanHalfDay' => null,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => '',
                        'pm' => '',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'message' => ErrorMessageEnum::TS_LUNCH_BREAK_UNEXPECTED_VALUE->value,
                    'propertyPath' => 'workDays[0].lunchBreak',
                ],
            ],
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
    public function testCreateTimesheetLocationIncoherentWithWorkedDay(): void
    {

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Monday->value => [
                    'day' => WeekDayEnum::Monday->value,
                    'projectTime' => 7.40,
                    'isMinDailyRestMet' => true,
                    'isWorkShiftValid' => true,
                    'workedMoreThanHalfDay' => true,
                    'lunchBreak' => 1,
                    'location' => [
                        'am' => null,
                        'pm' => null,
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'workDays[1].location.am',
                    'message' => ErrorMessageEnum::TS_LOCATION_MISSING->value,
                ],
                [
                    'propertyPath' => 'workDays[1].location.pm',
                    'message' => ErrorMessageEnum::TS_LOCATION_MISSING->value,
                ],
            ],
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

        $response = $this->postTimesheet($this->defaultTimesheetPayload([
            'endPeriod' => '2024-11-30',
            'workDays' => [
                WeekDayEnum::Sunday->value => [
                    'day' => WeekDayEnum::Sunday->value,
                    'projectTime' => 0,
                    'isMinDailyRestMet' => null,
                    'isWorkShiftValid' => null,
                    'workedMoreThanHalfDay' => null,
                    'lunchBreak' => 0,
                    'location' => [
                        'am' => 'test',
                        'pm' => 'test',
                    ],
                    'comment' => '',
                ],
            ],
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'workDays[0].location.am',
                    'message' => ErrorMessageEnum::TS_LOCATION_UNEXPECTED_VALUE->value,
                ],
                [
                    'propertyPath' => 'workDays[0].location.pm',
                    'message' => ErrorMessageEnum::TS_LOCATION_UNEXPECTED_VALUE->value,
                ],
            ],
        ]);
    }
}
