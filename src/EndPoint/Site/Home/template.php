<?php

declare(strict_types=1);

use Yiisoft\View\WebView;

/**
 * @var WebView $this
 */

$this->setTitle('Welcome to Yii3 Demo Blog');
?>

<div class="container">
    <div class="hero-section text-center py-5">
        <h1 class="display-4 mb-4">Welcome to Yii3 Demo Blog</h1>
        <p class="lead mb-4">
            Explore modern web development with <strong>Yii3 Framework</strong> —
            featuring clean architecture, dependency injection, and powerful components.
        </p>
        <div class="mt-5">
            <h3>Get Started</h3>
            <p class="mb-4">Ready to explore? Check out these resources:</p>
            <div class="btn-group">
                <a href="https://github.com/yiisoft/docs/blob/master/guide/en/README.md" class="btn btn-outline-primary">
                    <i class="bi bi-book me-1"></i> Documentation
                </a>
                <a href="https://github.com/yiisoft/demo-blog" class="btn btn-outline-primary">
                    <i class="bi bi-github me-1"></i> Source Code
                </a>
                <a href="https://www.yiiframework.com/" class="btn btn-outline-primary">
                    <i class="bi bi-globe me-1"></i> Yii Framework
                </a>
            </div>
        </div>
        <div class="mt-5">
            <div class="alert alert-secondary">
                <h5><i class="bi bi-terminal me-1"></i> Admin User</h5>
                <p class="mt-4 mb-2">Create your first admin account via console:</p>
                <code>./yii user:create-admin admin q1w2e3r4</code>
                <p class="mt-4 mb-2">Or fill the database with fake data:</p>
                <code>./yii fake-data</code>
            </div>
        </div>
    </div>
</div>
