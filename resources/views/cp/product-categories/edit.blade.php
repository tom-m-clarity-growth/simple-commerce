@extends('statamic::layout')
@section('title', 'Edit Product Category')
@section('wrapper_class', 'max-w-3xl')

@section('content')
    <publish-form
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
        title="{{ $values['title'] }}"
        name="product-category-publish-form"
        :breadcrumbs='@json($crumbs)'
        action="{{ $action }}"
        method="post"
        @saved="window.location.reload()"
    ></publish-form>
@endsection
