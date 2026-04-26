@if($actions->count() > 0)
    <div class="w-full overflow-x-auto">
        <table class="w-full min-w-[900px] table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold whitespace-nowrap w-[140px]">Role</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold whitespace-nowrap w-[180px]">Action</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold whitespace-nowrap w-[190px]">Timestamp</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold min-w-[360px]">Details</th>
                </tr>
            </thead>

            <tbody>
                @foreach($actions as $action)
                    @php
                        $role = \Illuminate\Support\Str::headline(data_get($action->action_data, 'role_name', 'N/A'));
                        $actionType = data_get($action, 'action_type', '');

                        $details = data_get($action->action_data, 'properties', []);

                        if (is_string($details)) {
                            $decoded = json_decode($details, true);
                            $details = is_array($decoded) ? $decoded : [];
                        }

                        if (!is_array($details)) {
                            $details = [];
                        }

                        $itemBlocks = [];
                        $paymentBreakdownBlocks = [];

                        $formatSimpleValue = function ($value) {
                            if (is_bool($value)) {
                                return $value ? 'Yes' : 'No';
                            }

                            if (is_null($value) || $value === '') {
                                return 'N/A';
                            }

                            if (is_array($value)) {
                                return json_encode($value);
                            }

                            return $value;
                        };

                        $cleanDetails = collect($details)
                            ->reject(function ($value, $key) {
                                $key = strtolower((string) $key);

                                return \Illuminate\Support\Str::contains($key, 'id')
                                    || $key === 'performed_by'
                                    || $key === 'role'
                                    || $key === 'items'
                                    || $key === 'payment_breakdown';
                            })
                            ->mapWithKeys(function ($value, $key) use ($formatSimpleValue) {
                                return [\Illuminate\Support\Str::headline((string) $key) => $formatSimpleValue($value)];
                            })
                            ->filter(function ($value) {
                                return $value !== 'N/A' && $value !== '' && $value !== '[]';
                            })
                            ->toArray();

                        if (!empty($details['items'])) {
                            $items = $details['items'];

                            if (is_string($items)) {
                                $decodedItems = json_decode($items, true);
                                $items = is_array($decodedItems) ? $decodedItems : [];
                            }

                            if (is_array($items)) {
                                $itemBlocks = collect($items)
                                    ->map(function ($item) use ($formatSimpleValue) {
                                        if (!is_array($item)) {
                                            return null;
                                        }

                                        return collect($item)
                                            ->reject(function ($value, $key) {
                                                $key = strtolower((string) $key);

                                                return \Illuminate\Support\Str::contains($key, 'id')
                                                    || $key === 'performed_by'
                                                    || $key === 'role'
                                                    || $key === 'status';
                                            })
                                            ->mapWithKeys(function ($value, $key) use ($formatSimpleValue) {
                                                return [\Illuminate\Support\Str::headline((string) $key) => $formatSimpleValue($value)];
                                            })
                                            ->filter(function ($value) {
                                                return $value !== 'N/A' && $value !== '' && $value !== '[]';
                                            })
                                            ->toArray();
                                    })
                                    ->filter()
                                    ->values()
                                    ->all();
                            }
                        }

                        if (!empty($details['payment_breakdown'])) {
                            $paymentBreakdown = $details['payment_breakdown'];

                            if (is_string($paymentBreakdown)) {
                                $decodedBreakdown = json_decode($paymentBreakdown, true);
                                $paymentBreakdown = is_array($decodedBreakdown) ? $decodedBreakdown : [];
                            }

                            if (is_array($paymentBreakdown)) {
                                $paymentBreakdownBlocks = collect($paymentBreakdown)
                                    ->map(function ($entry) use ($formatSimpleValue) {
                                        if (!is_array($entry)) {
                                            return null;
                                        }

                                        return collect($entry)
                                            ->reject(function ($value, $key) {
                                                $key = strtolower((string) $key);

                                                return \Illuminate\Support\Str::contains($key, 'id')
                                                    || $key === 'status'
                                                    || $key === 'performed_by'
                                                    || $key === 'role';
                                            })
                                            ->mapWithKeys(function ($value, $key) use ($formatSimpleValue) {
                                                return [\Illuminate\Support\Str::headline((string) $key) => $formatSimpleValue($value)];
                                            })
                                            ->filter(function ($value) {
                                                return $value !== 'N/A'
                                                    && $value !== ''
                                                    && $value !== '[]'
                                                    && $value !== 'null';
                                            })
                                            ->toArray();
                                    })
                                    ->filter()
                                    ->values()
                                    ->all();
                            }
                        }

                        $sentences = [];

                        if ($actionType === 'inventory.updated') {
                            if (!empty($cleanDetails['Name'])) {
                                $sentences[] = 'Updated inventory item "' . $cleanDetails['Name'] . '".';
                            }
                        } elseif ($actionType === 'inventory.restocked') {
                            if (!empty($cleanDetails['Items Restocked'])) {
                                $sentences[] = 'Restocked item(s): ' . $cleanDetails['Items Restocked'] . '.';
                            } else {
                                $parts = [];

                                if (!empty($cleanDetails['Name'])) {
                                    $parts[] = $cleanDetails['Name'];
                                }

                                if (!empty($cleanDetails['Added Quantity Input'])) {
                                    $qty = $cleanDetails['Added Quantity Input'];
                                    $unit = $cleanDetails['Added Unit Input'] ?? '';
                                    $parts[] = 'added quantity: ' . trim($qty . ' ' . $unit);
                                }

                                if (!empty($parts)) {
                                    $sentences[] = 'Restocked ' . implode(', ', $parts) . '.';
                                }
                            }
                        } elseif ($actionType === 'edit.menu.item') {
                            if (!empty($cleanDetails['Menu Item Name'])) {
                                $sentence = 'Edited menu item "' . $cleanDetails['Menu Item Name'] . '"';

                                if (!empty($cleanDetails['Price'])) {
                                    $sentence .= ' with price ' . $cleanDetails['Price'];
                                }

                                $sentences[] = $sentence . '.';
                            }
                        } elseif ($actionType === 'menu.created') {
                            if (!empty($cleanDetails['Name'])) {
                                $sentences[] = 'Created menu item "' . $cleanDetails['Name'] . '".';
                            }
                        } elseif ($actionType === 'menu.deleted') {
                            if (!empty($cleanDetails['Name'])) {
                                $sentences[] = 'Deleted menu item "' . $cleanDetails['Name'] . '".';
                            }
                        } elseif ($actionType === 'discount.created') {
                            if (!empty($cleanDetails['Name'])) {
                                $sentences[] = 'Created discount "' . $cleanDetails['Name'] . '".';
                            }
                        } elseif ($actionType === 'discount.updated') {
                            if (!empty($cleanDetails['Name'])) {
                                $sentences[] = 'Updated discount "' . $cleanDetails['Name'] . '".';
                            }
                        } elseif ($actionType === 'discount.deleted') {
                            if (!empty($cleanDetails['Name'])) {
                                $sentences[] = 'Deleted discount "' . $cleanDetails['Name'] . '".';
                            }
                        } elseif ($actionType === 'table.created') {
                            if (!empty($cleanDetails['Table Name'])) {
                                $sentences[] = 'Created table "' . $cleanDetails['Table Name'] . '".';
                            }
                        } elseif ($actionType === 'table.updated') {
                            if (!empty($cleanDetails['Table Name'])) {
                                $sentences[] = 'Updated table "' . $cleanDetails['Table Name'] . '".';
                            }
                        } elseif ($actionType === 'table.deleted') {
                            if (!empty($cleanDetails['Table Name'])) {
                                $sentences[] = 'Deleted table "' . $cleanDetails['Table Name'] . '".';
                            }
                        } elseif ($actionType === 'order.created') {
                            $sentences[] = 'Created a new order.';
                        } elseif ($actionType === 'order.updated') {
                            $sentences[] = 'Updated an order.';
                        } elseif ($actionType === 'payment.processed' || $actionType === 'payment.completed') {
                            if (!empty($paymentBreakdownBlocks)) {
                                foreach ($paymentBreakdownBlocks as $entry) {
                                    $parts = [];

                                    if (!empty($entry['Amount'])) {
                                        $parts[] = 'paid ₱' . number_format((float) $entry['Amount'], 2);
                                    }

                                    if (!empty($entry['Method'])) {
                                        $parts[] = 'via ' . ucfirst((string) $entry['Method']);
                                    } elseif (!empty($entry['Type'])) {
                                        $parts[] = 'via ' . ucfirst((string) $entry['Type']);
                                    }

                                    if (!empty($entry['Received'])) {
                                        $parts[] = 'received ₱' . number_format((float) $entry['Received'], 2);
                                    }

                                    if (!empty($entry['Change'])) {
                                        $parts[] = 'change ₱' . number_format((float) $entry['Change'], 2);
                                    }

                                    if (!empty($parts)) {
                                        $sentences[] = ucfirst(implode(', ', $parts)) . '.';
                                    }
                                }
                            } else {
                                $sentences[] = 'Processed a payment.';
                            }
                        }

                        if (empty($sentences) && !empty($cleanDetails)) {
                            foreach ($cleanDetails as $label => $value) {
                                $sentences[] = $label . ': ' . $value;
                            }
                        }
                    @endphp

                    <tr class="border-t align-top hover:bg-gray-50">
                        <td class="px-4 py-4 text-sm text-gray-800 whitespace-nowrap">
                            {{ $role }}
                        </td>

                        <td class="px-4 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                            {{ \Illuminate\Support\Str::headline(str_replace('.', ' ', $actionType)) }}
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-700 whitespace-nowrap">
                            {{ optional($action->timestamp)->format('M d, Y h:i A') }}
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-700">
                            @if(!empty($sentences) || !empty($itemBlocks) || !empty($paymentBreakdownBlocks))
                                <div class="space-y-3">
                                    @foreach($sentences as $sentence)
                                        <div class="text-gray-700 leading-6">
                                            {{ $sentence }}
                                        </div>
                                    @endforeach

                                    @if(!empty($itemBlocks))
                                        <div>
                                            <div class="font-medium text-gray-800 mb-2">Items</div>

                                            <div class="space-y-2">
                                                @foreach($itemBlocks as $index => $item)
                                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                                        <div class="text-xs font-semibold text-gray-500 mb-2">
                                                            Item {{ $index + 1 }}
                                                        </div>

                                                        <div class="space-y-1">
                                                            @foreach($item as $label => $value)
                                                                <div class="text-sm text-gray-700">
                                                                    <span class="font-medium text-gray-800">{{ $label }}:</span>
                                                                    {{ $value }}
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($paymentBreakdownBlocks))
                                        <div>
                                            <div class="font-medium text-gray-800 mb-2">Payment Breakdown</div>

                                            <div class="space-y-2">
                                                @foreach($paymentBreakdownBlocks as $index => $entry)
                                                    <div class="rounded-lg border border-gray-200 bg-blue-50 p-3">
                                                        <div class="text-xs font-semibold text-gray-500 mb-2">
                                                            Payment {{ $index + 1 }}
                                                        </div>

                                                        <div class="space-y-1">
                                                            @foreach($entry as $label => $value)
                                                                <div class="text-sm text-gray-700">
                                                                    <span class="font-medium text-gray-800">{{ $label }}:</span>
                                                                    @if(in_array($label, ['Amount', 'Received', 'Change', 'Total Due']))
                                                                        ₱{{ number_format((float) $value, 2) }}
                                                                    @else
                                                                        {{ $value }}
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">No details</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $actions->links() }}
    </div>
@else
    <div class="border rounded-lg p-8 text-center text-gray-500">
        No evaluated actions found.
    </div>
@endif