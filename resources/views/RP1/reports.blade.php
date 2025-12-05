@extends(env('APP_NAME') . '.index')
@section('main')
        <div class="card mt-2">
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td class="custom-cell">
                                <div class="header-section">Consumo Commessa</div>
                                <div class="value-section" data-key="consumo_commessa">{{ number_format($consumo_commessa/100, 2) }} m</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell">
                                <div class="header-section">Tempo Commessa</div>
                                <div class="value-section" data-key="tempo_commessa">{{ \Carbon\CarbonInterval::seconds($tempo_commessa)->cascade()->forHumans(['short' => true, 'options' => 0]) }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell">
                                <div class="header-section">Consumo Totale</div>
                                <div class="value-section" data-key="consumo_totale">{{ number_format($consumo_totale/100, 2) }} m</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="custom-cell">
                                <div class="header-section">Tempo Totale</div>
                                <div class="value-section" data-key="tempo_totale">{{ \Carbon\CarbonInterval::seconds($tempo_totale)->cascade()->forHumans(['short' => true, 'options' => 0]) }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
@endsection
