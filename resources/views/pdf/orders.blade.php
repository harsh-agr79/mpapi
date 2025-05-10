<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Summary</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }

        .header,
        .footer {
            text-align: center;
        }

        .header h2 {
            margin: 0;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h4 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .info-table,
        .items-table,
        .amounts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td,
        .items-table th,
        .items-table td,
        .amounts-table td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        .items-table th {
            background-color: #f9f9f9;
        }

        .amounts-table td.label {
            font-weight: bold;
            width: 50%;
        }

        .amounts-table td.value.warning {
            color: #e67e22;
        }
    </style>
</head>

<body>

    @foreach ($orders as $order)
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="{{ public_path('logo/long.png') }}" alt="Logo" style="height: 60px;">
        </div>
        <div class="header">
            <h2>Order Summary</h2>
            <p>Order ID: #{{ $order->id }} | Date: {{ $order->order_date }}</p>
        </div>

        <div class="section">
            <h4>Billing Details</h4>
            <table class="info-table">
                <tr>
                    <td><strong>Name</strong></td>
                    <td>{{ $order->billing_full_name }}</td>
                </tr>
                <tr>
                    <td><strong>Address</strong></td>
                    <td>
                        @php
                            $billingParts = array_filter([
                                $order->billing_street_address,
                                $order->billing_municipality,
                                $order->billing_city,
                                $order->billing_state,
                                $order->billing_country_region,
                                $order->billing_postal_code,
                            ]);
                        @endphp
                        {{ implode(', ', $billingParts) }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h4>Shipping Details</h4>
            <table class="info-table">
                <tr>
                    <td><strong>Name</strong></td>
                    <td>{{ $order->shipping_full_name }}</td>
                </tr>
                <tr>
                    <td><strong>Address</strong></td>
                    <td>
                        @php
                            $shippingParts = array_filter([
                                $order->shipping_street_address,
                                $order->shipping_municipality,
                                $order->shipping_city,
                                $order->shipping_state,
                                $order->shipping_country_region,
                                $order->shipping_postal_code,
                            ]);
                        @endphp
                        {{ implode(', ', $shippingParts) }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h4>Order Status</h4>
            <table class="info-table">
                <tr>
                    <td><strong>Order Status</strong></td>
                    <td>{{ ucfirst($order->current_status) }}</td>
                </tr>
                <tr>
                    <td><strong>Payment Status</strong></td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h4>Order Items</h4>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Color</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->OrderItem as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->color ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>NPR {{ number_format($item->discounted_price, 2) }}</td>
                            <td>NPR {{ number_format($item->quantity * $item->discounted_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section">
            <h4>Amount Summary</h4>
            <table class="amounts-table">
                <tr>
                    <td class="label">Sub-total</td>
                    <td class="value">NPR {{ number_format($order->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Discount</td>
                    <td class="value">NPR {{ number_format($order->discount, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Total</td>
                    <td class="value">NPR {{ number_format($order->discounted_total, 2) }}</td>
                </tr>
                @if ($order->coupon_code)
                    <tr>
                        <td class="label">Coupon Code</td>
                        <td class="value">{{ $order->coupon_code }}</td>
                    </tr>
                    <tr>
                        <td class="label">Coupon Discount</td>
                        <td class="value">NPR {{ number_format($order->coupon_discount, 2) }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="label">Delivery Fee</td>
                    <td class="value">NPR {{ number_format($order->delivery_charge, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Grand Total</td>
                    <td class="value warning">NPR {{ number_format($order->net_total, 2) }}</td>
                </tr>
            </table>
        </div>

        @if ($loop->last)
            <div class="footer">
                <p>Generated at: {{ now()->format('Y-m-d H:i:s') }}</p>
            </div>
        @endif
        @if (!$loop->last)
            <div style="page-break-before: always;"></div>
        @endif
    @endforeach


</body>

</html>
