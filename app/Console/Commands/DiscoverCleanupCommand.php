<?php

namespace App\Console\Commands;

use App\Support\Discover\DiscoverRepository;
use Illuminate\Console\Command;

class DiscoverCleanupCommand extends Command
{
    protected $signature = 'discover:cleanup {--days=90 : Delete entries older than this many days}';

    protected $description = 'Delete old Discover JSON entries from private storage.';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $deleted = DiscoverRepository::default()->cleanupOlderThan($days);

        $this->info("Deleted {$deleted} Discover entr".($deleted === 1 ? 'y' : 'ies')." older than {$days} days.");

        return self::SUCCESS;
    }
}
