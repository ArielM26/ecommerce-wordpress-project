<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Producto_Theme
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<script type="text/javascript">
// Función para validar RUT chileno
function validarRut(rut) {
    console.log('Validando RUT:', rut);
    
    // Limpiar el RUT: quitar puntos, guiones y espacios
    rut = rut.replace(/[^0-9kK]/g, '');
    console.log('RUT limpio:', rut);
    
    // Verificar que tenga al menos 2 caracteres
    if (rut.length < 2) {
        console.log('RUT muy corto');
        return false;
    }
    
    // Separar número del dígito verificador
    var numero = rut.slice(0, -1);
    var dv = rut.slice(-1).toUpperCase();
    console.log('Número:', numero, 'DV:', dv);
    
    // Verificar que el número sea numérico
    if (!/^\d+$/.test(numero)) {
        console.log('Número no es numérico');
        return false;
    }
    
    // Calcular el dígito verificador
    var suma = 0;
    var multiplicador = 2;
    
    // Recorrer el número de derecha a izquierda
    for (var i = numero.length - 1; i >= 0; i--) {
        suma += parseInt(numero[i]) * multiplicador;
        multiplicador++;
        if (multiplicador > 7) {
            multiplicador = 2;
        }
    }
    
    var resto = suma % 11;
    var dvCalculado = 11 - resto;
    
    // Determinar el dígito verificador correcto
    if (dvCalculado === 11) {
        dvCalculado = '0';
    } else if (dvCalculado === 10) {
        dvCalculado = 'K';
    } else {
        dvCalculado = dvCalculado.toString();
    }
    
    console.log('DV calculado:', dvCalculado, 'DV ingresado:', dv);
    
    // Comparar con el dígito verificador proporcionado
    return dv === dvCalculado;
}

// Función para formatear RUT con puntos y guión
function formatearRut(rut) {
    var rutLimpio = rut.replace(/[^0-9kK]/g, '');
    
    if (rutLimpio.length < 2) {
        return rut;
    }
    
    var numero = rutLimpio.slice(0, -1);
    var dv = rutLimpio.slice(-1).toUpperCase();
    
    // Formatear con puntos cada 3 dígitos desde la derecha
    var numeroFormateado = numero.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    return numeroFormateado + '-' + dv;
}

// Función para mostrar mensaje de error
function mostrarError(campo, mensaje) {
    console.log('Mostrando error:', mensaje);
    
    // Remover mensajes de error previos
    var errorPrevio = campo.parentNode.querySelector('.rut-error-message');
    if (errorPrevio) {
        errorPrevio.remove();
    }
    
    // Crear elemento de error
    var errorDiv = document.createElement('div');
    errorDiv.className = 'rut-error-message';
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '12px';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = mensaje;
    
    // Insertar después del campo
    campo.parentNode.insertBefore(errorDiv, campo.nextSibling);
    
    // Agregar clase de error al campo
    campo.classList.add('rut-invalido');
    campo.classList.remove('rut-valido');
}

// Función para limpiar error
function limpiarError(campo) {
    console.log('Limpiando error');
    var errorPrevio = campo.parentNode.querySelector('.rut-error-message');
    if (errorPrevio) {
        errorPrevio.remove();
    }
    campo.classList.remove('rut-invalido');
    campo.classList.add('rut-valido');
}

// Función principal que se ejecuta cuando el DOM está listo
function inicializarValidacionRut() {
    console.log('Inicializando validación RUT...');
    
    // Buscar el campo RUT con múltiples selectores
    var campoRut = document.getElementById('section_one-campo_rut') || 
                   document.querySelector('[name="section_one-campo_rut"]') ||
                   document.querySelector('input[id*="rut"]') ||
                   document.querySelector('input[name*="rut"]');
    
    console.log('Campo RUT encontrado:', campoRut);
    
    if (!campoRut) {
        console.log('No se encontró el campo RUT. Elementos disponibles:');
        var inputs = document.querySelectorAll('input[type="text"]');
        inputs.forEach(function(input, index) {
            console.log('Input', index, '- ID:', input.id, 'Name:', input.name, 'Class:', input.className);
        });
        
        // Reintentar después de 2 segundos
        setTimeout(inicializarValidacionRut, 2000);
        return;
    }
    
    console.log('Configurando eventos para el campo RUT...');
    
    // Evento para formatear al perder el foco
    campoRut.addEventListener('blur', function() {
        console.log('Evento blur activado, valor:', this.value);
        var valor = this.value.trim();
        
        if (valor !== '') {
            if (validarRut(valor)) {
                this.value = formatearRut(valor);
                limpiarError(this);
            } else {
                mostrarError(this, 'RUT inválido. Verifique el formato y dígito verificador.');
            }
        }
    });
    
    // Evento para validar mientras se escribe
    var timeoutId;
    campoRut.addEventListener('input', function() {
        console.log('Evento input activado, valor:', this.value);
        var campo = this;
        
        clearTimeout(timeoutId);
        
        timeoutId = setTimeout(function() {
            var valor = campo.value.trim();
            
            if (valor === '') {
                campo.classList.remove('rut-valido', 'rut-invalido');
                limpiarError(campo);
                return;
            }
            
            if (validarRut(valor)) {
                limpiarError(campo);
            } else {
                mostrarError(campo, 'RUT inválido. Verifique el formato y dígito verificador.');
            }
        }, 1000);
    });
    
    // Evento para limpiar formato al hacer focus
    campoRut.addEventListener('focus', function() {
        console.log('Evento focus activado');
        if (this.value.includes('.') || this.value.includes('-')) {
            this.value = this.value.replace(/[^0-9kK]/g, '');
        }
    });
    
    // Agregar placeholder si no existe
    if (!campoRut.placeholder) {
        campoRut.placeholder = 'Ej: 12345678-5';
    }
    
    // Interceptar envío de formularios
    var formulario = campoRut.closest('form');
    if (formulario) {
        console.log('Formulario encontrado, agregando validación de envío');
        formulario.addEventListener('submit', function(evento) {
            console.log('Formulario enviándose...');
            var valor = campoRut.value.trim();
            
            if (valor !== '' && !validarRut(valor)) {
                console.log('Previniendo envío por RUT inválido');
                evento.preventDefault();
                evento.stopPropagation();
                
                campoRut.focus();
                alert('Por favor, ingrese un RUT válido antes de continuar.');
                
                return false;
            }
        });
    }
    
    console.log('Validación RUT configurada correctamente');
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarValidacionRut);
} else {
    inicializarValidacionRut();
}

// También ejecutar cuando la página esté completamente cargada
window.addEventListener('load', function() {
    console.log('Página completamente cargada, reintentando inicialización...');
    setTimeout(inicializarValidacionRut, 1000);
});

// Para sitios con carga dinámica (AJAX), reintentar periódicamente
var intentos = 0;
var maxIntentos = 10;
var intervalo = setInterval(function() {
    intentos++;
    console.log('Intento', intentos, 'de encontrar campo RUT...');
    
    var campo = document.getElementById('section_one-campo_rut');
    if (campo && !campo.hasAttribute('data-rut-configurado')) {
        console.log('Campo encontrado en intento', intentos);
        campo.setAttribute('data-rut-configurado', 'true');
        inicializarValidacionRut();
        clearInterval(intervalo);
    }
    
    if (intentos >= maxIntentos) {
        console.log('Máximo de intentos alcanzado');
        clearInterval(intervalo);
    }
}, 2000);

console.log('Script de validación RUT cargado');
</script>

<style>
/* Estilos para la validación visual del RUT */
.rut-valido {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
}

.rut-invalido {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.rut-error-message {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #dc3545;
    font-weight: 400;
}

/* Animación suave para los cambios de estado */
input[id*="rut"], input[name*="rut"], #section_one-campo_rut {
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
</style>


	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'producto-theme' ); ?></a>

	<header id="masthead" class="site-header">
	<div class="site-branding">
		<?php if ( is_front_page() && is_home() ) : ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php else : ?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php endif; ?>
		
		<?php $description = get_bloginfo( 'description', 'display' ); ?>
		<?php if ( $description || is_customize_preview() ) : ?>
			<p class="site-description"><?php echo $description; ?></p>
		<?php endif; ?>
	</div>

	<nav id="site-navigation" class="main-navigation">
		<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menú', 'producto-theme' ); ?></button>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
			)
		);
		?>
		
		<?php if ( function_exists( 'WC' ) ) : ?>
			<div class="header-cart">
				<a href="<?php echo wc_get_cart_url(); ?>" class="cart-link">
					🛒 Carrito (<?php echo WC()->cart->get_cart_contents_count(); ?>)
				</a>
			</div>
		<?php endif; ?>
	</nav>
</header>
	</header><!-- #masthead -->
