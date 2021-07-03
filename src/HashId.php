<?php
/*
 * Copyright 2021 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace LaravelJsonApi\HashIds;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
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
     * @var bool
     */
    private bool $encode = true;

    /**
     * Get the default connection.
     *
     * @return string|null
     */
    public static function defaultConnection(): ?string
    {
        return self::$defaultConnection;
    }

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
     * Use the default pattern with the specified minimum length.
     *
     * @param int $length
     * @return $this
     */
    public function withLength(int $length): self
    {
        if (0 < $length) {
            return $this->matchAs(sprintf('[a-zA-Z0-9]{%d,}', $length));
        }

        throw new InvalidArgumentException('Expecting an integer greater than zero.');
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
     * Mark the field as receiving model keys that are already encoded.
     *
     * If the developer has encoded model keys to hash ids in the `Model::getRouteKey()`
     * method, then the value received by this HashId class will already be encoded.
     * In this scenario, the HashId will need to just return the model key value,
     * rather than attempting to encode it - so this method should be called so that
     * we know not to encode the model key.
     *
     * @return $this
     */
    public function alreadyHashed(): self
    {
        $this->encode = false;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function encode($modelKey): string
    {
        if (true === $this->encode) {
            return $this->hashIds()->encode(
                $modelKey
            );
        }

        if (is_string($modelKey)) {
            return $modelKey;
        }

        throw new \RuntimeException('Expecting model key to already be encoded to a string.');
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
    public function fill(Model $model, $value, array $validatedData): void
    {
        if ($decoded = $this->decode($value)) {
            parent::fill($model, $decoded, $validatedData);
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
            $this->connection ?? self::defaultConnection(),
        );
    }

}
