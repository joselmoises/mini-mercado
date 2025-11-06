<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Pedido</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">✓ Pedido Confirmado!</h1>
                            <p style="margin: 10px 0 0 0; color: #d1fae5; font-size: 16px;">Pedido #{{ $order->id }}</p>
                        </td>
                    </tr>

                    <!-- Greeting -->
                    <tr>
                        <td style="padding: 30px 30px 20px 30px;">
                            <p style="margin: 0; color: #1f2937; font-size: 16px; line-height: 24px;">
                                Olá <strong>{{ $order->user->name }}</strong>,
                            </p>
                            <p style="margin: 15px 0 0 0; color: #6b7280; font-size: 14px; line-height: 22px;">
                                Obrigado pela sua compra! O seu pedido foi confirmado com sucesso e está a ser processado.
                            </p>
                        </td>
                    </tr>

                    <!-- Order Items -->
                    <tr>
                        <td style="padding: 20px 30px;">
                            <h2 style="margin: 0 0 20px 0; color: #1f2937; font-size: 18px; font-weight: 600; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">
                                Detalhes do Pedido
                            </h2>

                            @foreach($order->items as $item)
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 15px; background-color: #f9fafb; border-radius: 6px; overflow: hidden;">
                                <tr>
                                    <td width="80" style="padding: 15px;">
                                        @if(!empty($item->product_image))
                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" width="80" height="80" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; display: block; border: 0; max-width: 80px;" border="0">
                                        @else
                                        <div style="width: 80px; height: 80px; background-color: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 12px;">
                                            Sem imagem
                                        </div>
                                        @endif
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle;">
                                        <p style="margin: 0; color: #1f2937; font-size: 15px; font-weight: 600;">{{ $item->product_name }}</p>
                                        <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 13px;">Quantidade: {{ $item->quantity }}</p>
                                    </td>
                                    <td width="100" style="padding: 15px; text-align: right; vertical-align: middle;">
                                        <p style="margin: 0; color: #10b981; font-size: 16px; font-weight: 700;">{{ number_format($item->price * $item->quantity, 2, ',', '.') }} MT</p>
                                    </td>
                                </tr>
                            </table>
                            @endforeach
                        </td>
                    </tr>

                    <!-- Order Summary -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f9fafb; border-radius: 6px; padding: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; color: #6b7280; font-size: 14px;">Método de Pagamento</p>
                                    </td>
                                    <td align="right">
                                        <p style="margin: 0; color: #1f2937; font-size: 14px; font-weight: 600;">
                                            @if($order->payment_method === 'card')
                                                Cartão
                                            @elseif($order->payment_method === 'mpesa')
                                                M-Pesa
                                            @else
                                                {{ ucfirst($order->payment_method) }}
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding-top: 15px; border-top: 1px solid #e5e7eb; margin-top: 15px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <p style="margin: 15px 0 0 0; color: #1f2937; font-size: 18px; font-weight: 700;">Total</p>
                                                </td>
                                                <td align="right">
                                                    <p style="margin: 15px 0 0 0; color: #10b981; font-size: 24px; font-weight: 700;">{{ number_format($order->total, 2, ',', '.') }} MT</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Action Button -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;" align="center">
                            <a href="{{ route('orders.show', $order) }}" style="display: inline-block; padding: 14px 40px; background-color: #10b981; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);">
                                Ver Detalhes do Pedido
                            </a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px; background-color: #f9fafb; border-top: 1px solid #e5e7eb; text-align: center;">
                            <p style="margin: 0; color: #6b7280; font-size: 14px;">
                                Obrigado por comprar no <strong style="color: #1f2937;">{{ config('app.name') }}</strong>
                            </p>
                            <p style="margin: 10px 0 0 0; color: #9ca3af; font-size: 12px;">
                                Se tiver alguma dúvida, não hesite em contactar-nos.
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Footer Info -->
                <table width="600" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
                    <tr>
                        <td style="text-align: center; color: #9ca3af; font-size: 12px; line-height: 18px;">
                            <p style="margin: 0;">© {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
