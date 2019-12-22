<?php

namespace Softpay\Parcelamento\Commands;

use Illuminate\Console\Command;

/**
 * List all locally installed packages.
 *
 * @author Softpay
 **/
class ListPackages extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'Parcelamento:list';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'List all locally installed packages.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $packages_path = base_path('packages/');
        $repositories = $composer['repositories'] ?? [];
        $packages = [];
        foreach ($repositories as $name => $info) {
            $path = $info['url'];
            $pattern = '{'.addslashes($packages_path).'(.*)$}';
            if (preg_match($pattern, $path, $match)) {
                $packages[] = explode(DIRECTORY_SEPARATOR, $match[1]);
            }
        }

        $headers = ['Package', 'Path'];
        $this->table($headers, $packages);
    }
}
