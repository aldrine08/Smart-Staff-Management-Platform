<?php

return [
    /*
     * Only requests created on or after this instant
     * are eligible for the ledger approval workflow.
     *
     * Example:
     * 2026-11-01T00:00:00+03:00
     */
    'go_live_at' => env('OFFDAY_LEDGER_GO_LIVE_AT'),

    /*
     * Hard ceiling for a single request.
     * An admin may still approve fewer days only
     * by asking the employee to edit the request.
     */
    'maximum_request_days' => 366,
];
