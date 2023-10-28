<?php

namespace App\Jobs;

use App\Parsers\EbayParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EbayParseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private EbayParser $parser;

    /**
     * Create a new job instance.
     */
    public function __construct(EbayParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->parser->startParse(false);
    }
}
