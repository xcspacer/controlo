<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pedido de Combustível</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <table style="width: 100%; max-width: 800px; margin: 0 auto; padding: 20px;" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                
                <!-- Header -->
                <table style="width: 100%; background-color: #f8f9fa; border-radius: 8px; margin-bottom: 20px;" cellpadding="20" cellspacing="0">
                    <tr>
                        <td>
                            <h2 style="margin: 0 0 15px 0; color: #333;">Pedido de Combustível</h2>
                            <p style="margin: 5px 0;"><strong>ID:</strong> {{ $requestData['request_id'] }}</p>
                            <p style="margin: 5px 0;"><strong>Solicitante:</strong> {{ $requestData['requested_by'] }}</p>
                            <p style="margin: 5px 0;"><strong>Data/Hora:</strong> {{ $requestData['requested_at'] }}</p>
                            <p style="margin: 5px 0;"><strong>Data de Entrega Prevista:</strong> {{ $requestData['delivery_date'] }}</p>
                        </td>
                    </tr>
                </table>
                
                <!-- Total Section -->
                <table style="width: 100%; background-color: #28a745; border-radius: 8px; margin: 20px 0;" cellpadding="15" cellspacing="0">
                    <tr>
                        <td style="text-align: center; color: white; font-size: 20px; font-weight: bold;">
                            Total do Pedido: {{ number_format($requestData['total_quantity'], 0, ',', '.') }} Litros
                        </td>
                    </tr>
                </table>
                
                <!-- Groups -->
                @foreach($requestData['organized_data'] as $groupData)
                <table style="width: 100%; border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 30px;" cellpadding="0" cellspacing="0">
                    
                    <!-- Group Header -->
                    <tr>
                        <td style="background-color: #e9ecef; padding: 15px; font-weight: bold; font-size: 18px;">
                            Grupo {{ $groupData['group'] }}
                        </td>
                    </tr>
                    
                    <!-- Stations -->
                    @foreach($groupData['stations'] as $stationId => $stationData)
                    <tr>
                        <td style="padding: 15px; border-bottom: 1px solid #dee2e6;">
                            
                            <!-- Station Info -->
                            <div style="font-weight: bold; color: #495057; margin-bottom: 15px;">
                                Posto: <span style="font-size: 20px;">{{ $stationData['name'] }}</span><br>
                                Morada: {{ $stationData['address'] }}<br>
                                Localidade: {{ $stationData['city'] }}
                            </div>
                            
                            <!-- Fuels Table -->
                            <table style="width: 100%;" cellpadding="0" cellspacing="0">
                                @foreach($stationData['fuels'] as $fuel)
                                <tr style="border-bottom: 1px dotted #dee2e6;">
                                    <td style="padding: 8px 0; text-align: left;">{{ $fuel['name'] }}</td>
                                    <td style="padding: 8px 0; text-align: right; font-weight: bold;">
                                        {{ number_format($fuel['quantity'], 0, ',', '.') }} LT
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            
                        </td>
                    </tr>
                    @endforeach
                    
                </table>
                @endforeach
                
                <!-- Notes Section -->
                @if($requestData['notes'])
                <table style="width: 100%; background-color: #fff3cd; border: 1px solid #ffeeba; border-radius: 8px; margin: 20px 0;" cellpadding="15" cellspacing="0">
                    <tr>
                        <td>
                            <strong>Observações:</strong><br>
                            {{ $requestData['notes'] }}
                        </td>
                    </tr>
                </table>
                @endif
                
                <!-- Footer -->
                <table style="width: 100%; margin-top: 30px; border-top: 2px solid #dee2e6;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding-top: 20px;">
                            <p style="margin: 0; font-style: italic; color: #666;">
                                <em>Este é um email automático gerado pelo sistema de gestão de combustíveis.</em>
                            </p>
                        </td>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>
</body>
</html>