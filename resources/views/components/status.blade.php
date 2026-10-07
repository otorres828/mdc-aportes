@props(['status' => 'pending'])
<span class="status {{ $status }}"><x-icon :name="$status === 'verified' ? 'check' : ($status === 'rejected' ? 'close' : 'clock')"/>{{ ['pending' => 'En verificación', 'verified' => 'Verificado', 'rejected' => 'Rechazado'][$status] ?? 'En verificación' }}</span>
