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
            <h4 class="mb-3">Quick Start</h4>
            <ul class="nav nav-tabs justify-content-center" id="quickStartTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="native-tab" data-bs-toggle="tab" data-bs-target="#native" type="button" role="tab" aria-controls="native" aria-selected="true">
                        <i class="bi bi-terminal me-1"></i> Native
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="docker-tab" data-bs-toggle="tab" data-bs-target="#docker" type="button" role="tab" aria-controls="docker" aria-selected="false">
                        <i class="bi bi-box me-1"></i> Docker
                    </button>
                </li>
            </ul>
            <div class="tab-content mt-3" id="quickStartTabContent">
                <div class="tab-pane fade show active" id="native" role="tabpanel" aria-labelledby="native-tab">
                    <div class="p-3 bg-light rounded">
                        <p class="mb-2">Start the development server:</p>
                        <code class="d-block mb-3">./yii serve</code>
                        <p class="mb-2">Create only admin account:</p>
                        <code class="d-block mb-3">./yii user:create-admin admin q1w2e3r4</code>
                        <p class="mb-2">Or fill the database with fake data:</p>
                        <code class="d-block">./yii fake-data</code>
                    </div>
                </div>
                <div class="tab-pane fade" id="docker" role="tabpanel" aria-labelledby="docker-tab">
                    <div class="p-3 bg-light rounded">
                        <p class="mb-2">Start the application:</p>
                        <code class="d-block mb-3">make up</code>
                        <p class="mb-2">Create only admin account (admin / q1w2e3r4):</p>
                        <code class="d-block mb-3">make create-admin</code>
                        <p class="mb-2">Or fill the database with fake data:</p>
                        <code class="d-block">make fake-data</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
