<?php

namespace App\Factory;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskStatus;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Task>
 */
final class TaskFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Task::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'title'       => self::faker()->sentence(3),
            'description' => self::faker()->paragraph(),
            'deadline'    => self::faker()->optional()->dateTimeBetween('now', '+1 month'),
            'status'      => self::faker()->randomElement(TaskStatus::cases()),
            'employee'    => EmployeeFactory::random(),
            'project'     => ProjectFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }
}
