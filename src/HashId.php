<?php

declare(strict_types=1);

namespace LaravelJsonApi\HashIds;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use LaravelJsonApi\Contracts\Schema\IdEncoder;
use LaravelJsonApi\Eloquent\Fields\ID;
use RuntimeException;

class HashId extends ID implements IdEncoder
{

    /**
     * The default hash ids connection.
     *
     * @var string|null
     */
    private static ?string $defaultConnection = null;

    /**
     * @var string|null
     */
    private ?string $connection = null;

    /**
     * Set the default hash ids connection.
     *
     * @param string|null $connection
     * @return void
     */
    public static function withDefaultConnection(?string $connection): void
    {
        self::$defaultConnection = $connection;
    }

    /**
     * HashId constructor.
     *
     * @param string|null $column
     */
    public function __construct(string $column = null)
    {
        parent::__construct($column);
        $this->matchAs('[a-zA-Z0-9]+');
    }

    /**
     * @param string|null $connection
     * @return $this
     */
    public function useConnection(?string $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function encode($modelKey): string
    {
        return $this->hashIds()->encode(
            $modelKey
        );
    }

    /**
     * @inheritDoc
     */
    public function decode(string $resourceId)
    {
        $decoded = $this->hashIds()->decode($resourceId);

        if (1 === count($decoded)) {
            return $decoded[0];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function fill(Model $model, $value): void
    {
        if ($decoded = $this->decode($value)) {
            parent::fill($model, $decoded);
            return;
        }

        throw new RuntimeException(
            'Resource ID did not decode to a model key. Ensure client IDs are correctly validated.'
        );
    }

    /**
     * @return Hashids
     */
    protected function hashIds(): Hashids
    {
        return \Vinkla\Hashids\Facades\Hashids::connection(
            $this->connection ?? self::$defaultConnection
        );
    }

}
