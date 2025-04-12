<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Order Update</title>
	</head>
	<body
		style="
			margin: 0;
			padding: 0;
			background-color: #f3f3f3;
			font-family: 'Montserrat', sans-serif;
		"
	>
		<table
			width="100%"
			cellpadding="0"
			cellspacing="0"
			border="0"
			style="background-color: #f3f3f3; padding: 40px 0"
		>
			<tr>
				<td align="center">
					<table
						width="800"
						cellpadding="0"
						cellspacing="0"
						border="0"
						style="
							background-color: #ffffff;
							border-radius: 8px;
							overflow: hidden;
						"
					>
						<!-- LOGO -->
						<tr>
							<td align="center" style="padding: 30px">
								<img
									src="{{ asset('logo/long.png') }}"
									alt="My Power Logo"
									style="width: 130px"
								/>
								<div
									style="
										width: 80px;
										height: 6px;
										background-color: #fecd07;
										margin-top: 10px;
									"
								></div>
							</td>
						</tr>

						<!-- BODY -->
						<tr>
							<td style="padding: 0 30px 30px 30px; text-align: center">
								<h1 style="font-size: 28px; color: #000">
									Your Order Status has Changed!
								</h1>
								<p>
									Your order has been
									<strong style="color: #fecd07">{{ strtoupper($order->current_status) }}</strong>.
								</p>
								<p style="margin: 20px 0">
									<a
										href="https://www.mypower.com.np/orders"
										style="
											background-color: #fecd07;
											padding: 12px 20px;
											color: #000;
											text-decoration: none;
											display: inline-block;
											border-radius: 4px;
										"
										>Manage My Order</a
									>
								</p>

								<!-- USER DETAILS -->
								<table
									width="100%"
									cellpadding="0"
									cellspacing="0"
									style="
										margin-top: 20px;
										font-size: 14px;
										color: #333;
										text-align: left;
										margin-bottom: 20px;
										margin-top: 20px;
									"
								>
									<tr>
										<td style="padding: 5px 0">
											<strong style="color: #fecd07">Order No.:</strong> #{{ $order->id }}
										</td>
									</tr>
									<tr>
										<td style="padding: 5px 0">
											<strong style="color: #fecd07">Name:</strong> {{ $order->billing_full_name }}
											Shrestha
										</td>
									</tr>
									<tr>
										<td style="padding: 5px 0">
											<strong style="color: #fecd07">Phone No.:</strong> {{ $order->billing_phone_number }}
										</td>
									</tr>
									<tr>
										<td style="padding: 5px 0">
											<strong style="color: #fecd07">Email:</strong>
											<a href="mailto:{{ $order->billing_email }}"
												>{{ $order->billing_email }}</a
											>
										</td>
									</tr>
									<tr>
										<td style="padding: 5px 0">
											<strong style="color: #fecd07">Shipping Address:</strong>
											{{ $order->shipping_street_address }},{{ $order->shipping_municipality }},{{ $order->shipping_city }},{{ $order->shipping_state }},{{ $order->shipping_postal_code }}
										</td>
									</tr>
									<tr>
										<td style="padding: 5px 0">
											<strong style="color: #fecd07">Billing Address:</strong>
											{{ $order->billing_street_address }},{{ $order->billing_municipality }},{{ $order->billing_city }},{{ $order->billing_state }},{{ $order->billing_postal_code }}
										</td>
									</tr>
									<tr>
										<td style="padding: 5px 0">
											<strong style="color: #fecd07">Order Status:</strong>
											<span
												style="
													background-color: #fecd07;
													padding: 5px 15px;
													border-radius: 100px;
												"
												>{{$order->current_status}}</span
											>
										</td>
									</tr>
									<tr>
										<td style="padding: 5px 0">
											<strong style="color: #fecd07">Payment Status:</strong>
											{{$order->payment_status}}
										</td>
									</tr>
								</table>

								<!-- ORDER TABLE -->
								<div style="width: 100%; overflow-x: auto">
									<table
										cellpadding="0"
										cellspacing="0"
										border="0"
										style="
											width: 100%;
											max-width: 750px;
											border-collapse: collapse;
											background: #ffffff;
											border-radius: 8px;
											overflow-x: auto;
										"
									>
										<thead>
											<tr
												style="
													background-color: #fecd07;
													color: #000000;
													text-align: left;
												"
											>
												<th style="padding: 12px 16px; font-weight: 600">
													Product Name
												</th>
												<th style="padding: 12px 16px; font-weight: 600">
													Price
												</th>
												<th style="padding: 12px 16px; font-weight: 600">
													Quantity
												</th>
												<th style="padding: 12px 16px; font-weight: 600">
													Subtotal
												</th>
											</tr>
										</thead>
										<tbody style="font-size: 14px; color: #555">
                                            @foreach($order->OrderItem as $item)
                                            <tr>
												<td style="padding: 12px 16px">
													{{$item->product->name}}
												</td>
												<td style="padding: 12px 16px">Rs. {{$item->discounted_price}}</td>
												<td style="padding: 12px 16px">{{$item->quantity}}</td>
												<td style="padding: 12px 16px">Rs. {{$item->discounted_price * $item->quantity}}</td>
											</tr>
                                            @endforeach
											

											<!-- Subtotal Row -->
											<tr style="background-color: #f7f7f7">
												<td
													colspan="3"
													style="padding: 12px 16px; text-align: right"
												>
													Subtotal
												</td>
												<td style="padding: 12px 16px">Rs. {{ $order->discounted_total }}</td>
											</tr>

											<!-- Delivery Row -->
											<tr>
												<td
													colspan="3"
													style="padding: 12px 16px; text-align: right"
												>
													Delivery Charge
												</td>
												<td style="padding: 12px 16px">Rs. {{ $order->delivery_charge }}</td>
											</tr>

											<!-- Grand Total -->
											<tr
												style="
													background-color: #f7f7f7;
													font-weight: bold;
													color: #000;
												"
											>
												<td
													colspan="3"
													style="padding: 12px 16px; text-align: right"
												>
													Grand Total
												</td>
												<td style="padding: 12px 16px">Rs. {{$order->net_total}}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
						</tr>

						<!-- Footer -->
						<table
							role="presentation"
							width="100%"
							max-width="800"
							cellspacing="0"
							cellpadding="0"
							border="0"
							style="
								background-color: #3d3d3d;
								padding: 20px;
								border-radius: 0 0 8px 8px;
								width: 100%;
								max-width: 800px;
							"
						>
							<tr>
								<td align="center">
									<!-- Social Icons -->
									<table
										role="presentation"
										cellspacing="0"
										cellpadding="5"
										border="0"
										style="text-align: center"
									>
										<tr>
											<td style="padding: 0 5px">
												<a
													href="https://www.facebook.com/profile.php?id=61557147477761"
													target="_blank"
													><img
														src="{{ asset('social_logos/facebook.png') }}"
														width="24"
														alt="Facebook"
												/></a>
											</td>
											<td style="padding: 0 5px">
												<a
													href="https://www.instagram.com/mypowernepal/"
													target="_blank"
													><img
														src="{{ asset('social_logos/instagram.png') }}"
														width="24"
														alt="Instagram"
												/></a>
											</td>
											<td style="padding: 0 5px">
												<a
													href="https://www.tiktok.com/@mypowernepal"
													target="_blank"
													><img
														src="{{ asset('social_logos/tiktok.png') }}"
														width="24"
														alt="Tiktok"
												/></a>
											</td>
											<td style="padding: 0 5px">
												<a
													href="https://www.youtube.com/@mypowernepal"
													target="_blank"
													><img
														src="{{ asset('social_logos/youtube.png') }}"
														width="24"
														alt="Youtube"
												/></a>
											</td>
											<td style="padding: 0 5px">
												<a href="https://x.com/mypowernepal" target="_blank"
													><img
														src="{{ asset('social_logos/x.png') }}"
														width="24"
														alt="Twitter"
												/></a>
											</td>
											<td style="padding: 0 5px">
												<a
													href="https://www.pinterest.com/Mypowernepal/"
													target="_blank"
													><img
														src="{{ asset('social_logos/pinterest.png') }}"
														width="24"
														alt="Pinterest"
												/></a>
											</td>
										</tr>
									</table>
									<br />
									<p style="color: white; font-size: 12px; text-align: center">
										© 2025 MyPower, All Rights Reserved
									</p>
									<p style="color: white; font-size: 12px; text-align: center">
										Reply to this email to contact us!
									</p>
								</td>
							</tr>
						</table>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>