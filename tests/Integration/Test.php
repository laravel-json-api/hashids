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

namespace LaravelJsonApi\HashIds\Tests\Integration;

use LaravelJsonApi\HashIds\HashId;
use Vinkla\Hashids\Facades\Hashids;

class Test extends TestCase
{

    public function test(): void
    {
        $id = HashId::make();

        $this->assertInstanceOf(HashId::class, $id);
        $this->assertNull($id->column());
        $this->assertSame('id', $id->name());
        $this->assertTrue($id->isSortable());
        $this->assertFalse($id->isSparseField());
        $this->assertFalse($id->acceptsClientIds());
    }

    public function testMatch(): void
    {
        $id = HashId::make();

        $this->assertTrue($id->match(Hashids::encode(
            random_int(1, 999999)
        )));
    }

    public function testEncode(): void
    {
        $id = HashId::make();
        $actual = $id->encode(10);

        $this->assertSame(Hashids::encode(10), $actual);
        $this->assertSame(10, $id->decode($actual));
    }

    public function testEncodeWithSpecifiedConnection(): void
    {
        $id = HashId::make()->useConnection('alternative');
        $actual = $id->encode(99);

        $this->assertSame(Hashids::connection('alternative')->encode(99), $actual);
        $this->assertSame(99, $id->decode($actual));
    }

    public function testEncodeWithDefaultConnection(): void
    {
        HashId::withDefaultConnection('alternative');

        $this->assertSame('alternative', HashId::defaultConnection());

        $id = HashId::make();
        $actual = $id->encode(10);

        $this->assertSame(Hashids::connection('alternative')->encode(10), $actual);
        $this->assertSame(10, $id->decode($actual));
    }

    public function testAlreadyHashed(): void
    {
        $id = HashId::make()->alreadyHashed();
        $value = Hashids::encode(1);

        $this->assertSame($value, $id->encode($value));
    }

    public function testAlreadyEncodedWithInvalidValue(): void
    {
        $this->expectException(\RuntimeException::class);
        HashId::make()->alreadyHashed()->encode(1);
    }

    public function testDecodeInvalid(): void
    {
        $value = Hashids::connection('alternative')->encode(99);

        $this->assertNull(HashId::make()->decode($value));
    }

    public function testDecodeMultipleNumbers(): void
    {
        $value = Hashids::encode(1, 2, 3);

        $this->assertNull(HashId::make()->decode($value));
    }

    public function testFill(): void
    {
        $id = HashId::make();
        $value = Hashids::encode(99);

        $id->fill($model = new TestModel(), $value, []);

        $this->assertSame(99, $model->getKey());
    }

    public function testFillWithSpecifiedConnection(): void
    {
        $id = HashId::make()->useConnection('alternative');
        $value = Hashids::connection('alternative')->encode(99);

        $id->fill($model = new TestModel(), $value, []);

        $this->assertSame(99, $model->getKey());
    }

    public function testFillWithDefaultConnection(): void
    {
        HashId::withDefaultConnection('alternative');

        $id = HashId::make();
        $value = Hashids::connection('alternative')->encode(99);

        $id->fill($model = new TestModel(), $value, []);

        $this->assertSame(99, $model->getKey());
    }

    public function testFillInvalid(): void
    {
        $id = HashId::make();
        $value = Hashids::connection('alternative')->encode(99);

        $this->expectException(\RuntimeException::class);
        $id->fill($model = new TestModel(), $value, []);
    }

    public function testFillMultipleNumbers(): void
    {
        $id = HashId::make();
        $value = Hashids::connection('alternative')->encode(1, 2, 3);

        $this->expectException(\RuntimeException::class);
        $id->fill($model = new TestModel(), $value, []);
    }
}
