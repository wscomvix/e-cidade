<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LegacyBuilder extends Builder
{
    /**
     * Colunas adicionadas no recurso, mas n�o na query
     *
     * @var array
     */
    private array $additional = [];

    /**
     * Colunas em query, mas n�o no recurso
     *
     * @var array
     */
    private array $except = [];

    /**
     * Filtros
     *
     * @var array
     */
    private array $filters = [];

    /**
     * Filtra por parametros
     *
     * @param array $data
     *
     * @return $this
     */
    public function filter(array $data = []): LegacyBuilder
    {
        $this->setFilters($data);
        $this->executeFilters();

        return $this;
    }

    /**
     * Retorna um recurso collection
     *
     * @param array $columns
     * @param array $additional
     *
     * @return Collection
     */
    public function resource(array $columns = ['*'], array $additional = []): Collection
    {
        $this->setAdditional($additional);
        $columnsNotExcept = $columns;
        $columns = array_merge($columns, $this->except);
        $columns = $this->replaceAttribute($columns);
        //original do laravel
        $resource = $this->get($columns);

        return $this->mapResource($resource, $columnsNotExcept);
    }

    /**
     * Transforma o recurso com os novos parametros
     *
     * @param Collection $resource
     * @param array      $columnsNotExcept
     *
     * @return Collection
     */
    private function mapResource(Collection $resource, array $columnsNotExcept): Collection
    {
        return $resource->map(function (Model $item) use ($columnsNotExcept) {
            $resource = [];
            //Trata colunas com alias do banco de dados
            foreach ($columnsNotExcept as $key) {
                if (Str::contains($key, ' as ')) {
                    [, $alias] = explode(' as ', $key);
                    $resource[$alias] = $item->{$alias};
                } else {
                    $resource[$key] = $item->{$key};
                }
            }
            //Trata colunas com alias adicionais
            foreach ($this->additional as $key) {
                if (Str::contains($key, ' as ')) {
                    [$key, $alias] = explode(' as ', $key);
                    $resource[$alias] = $item->{$key};
                } else {
                    $resource[$key] = $item->{$key};
                }
            }

            return $resource;
        });
    }

    /**
     * Colunas adicionais que n�o est�o na query, mas � adicionado no recurso
     *
     * @param array $additional
     *
     * @return LegacyBuilder
     */
    private function setAdditional(array $additional): LegacyBuilder
    {
        $this->additional = $additional;

        return $this;
    }

    /**
     * Colunas a serem adicionadas na query, mas n�o retorna no recurso
     *
     * @param array $except
     *
     * @return LegacyBuilder
     */
    public function setExcept(array $except): LegacyBuilder
    {
        $this->except = $except;

        return $this;
    }

    /**
     * Executa os filtros
     *
     * @return void
     */
    private function executeFilters(): void
    {
        foreach ($this->filters as $filter => $parameter) {
            $method = 'where' . $filter;
            if (is_array($parameter)) {
                $this->{$method}(...$parameter);

                continue;
            }
            $this->{$method}($parameter);
        }
    }

    /**
     * Substitui os atributos legados
     */
    private function replaceAttribute(array $columns): array
    {
        //parametro definido no model
        if (!property_exists($this->getModel(), 'legacy')) {
            return $columns;
        }
        $legacy = $this->getModel()->legacy;
        if (!is_array($legacy) || empty($legacy)) {
            return $columns;
        }
        $data = [];
        foreach ($columns as $key) {
            if (Str::contains($key, ' as ')) {
                [$key, $alias] = explode(' as ', $key);
                $legacyKey = $legacy[$key] ?? $key;
                $data[] = $legacyKey . ' as ' . $alias;
            } else {
                $data[] = $legacy[$key] ?? $key;
            }
        }
        if (!empty($data)) {
            $columns = $data;
        }

        return $columns;
    }

    /**
     * Insere os filtros personalizados ou do request
     *
     * @param array $filters
     *
     * @return void
     */
    private function setFilters(array $filters): void
    {
        $data = [];
        foreach ($filters as $key => $value) {
            $filter = $this->getFilterName($key);
            if ($this->checkWhereParameters($value, $filter)) {
                $data[$filter] = $value;
            }
            $this->filters = $data;
        }
    }

    public function checkWhereParameters($value, $filter)
    {
        return ((!is_array($value) && $value !== null && $value !== '') ||
                (is_array($value) && count(array_filter($value)) > 0)) &&
            method_exists($this, 'where' . $filter);
    }

    /**
     * Transforma o nome do parametro para o nome de filtro
     *
     * @param $name
     *
     * @return string
     */
    private function getFilterName($name): string
    {
        return Str::camel($name);
    }

}
