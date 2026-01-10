<?php

namespace App\Models\Concerns;

trait ConvertMarkdownToHTML
{
    protected static function bootConvertMarkdownToHTML()
    {
        static::saving(function (self $model) {
        $markdownData= collect(self::getMarkdownToHTMLmap())
            ->flip()
            ->map(fn($bodyCol)=>str($model->$bodyCol)->markdown([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
                'max_nesting_level' => 5,
        ]));
            return $model->fill($markdownData->all());
        });
    } 

    protected static function getMarkdownToHTMLmap(){
        return [
            'body' => 'html',
        ];
    }
}
