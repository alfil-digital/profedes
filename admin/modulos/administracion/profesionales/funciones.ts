// docedes-plantillas/plantilla/modulos/administracion/profesionales/funciones.ts
// MODIFICACIÓN: Cambiado el comentario de la ruta para reflejar el módulo de profesionales.

/**
 * Carga el formulario de edición o creación de un profesional.
 * @param id El ID del profesional a editar (0 para un nuevo registro).
 */
function editar(id: number): void {
    // Desplaza la página al principio (arriba del todo) de forma instantánea.
    window.scrollTo({
        top: 0,
        behavior: 'auto' // 'auto' para desplazamiento instantáneo
    });

    // Cargar el formulario de edición/creación del profesional.
    // Realiza una petición GET al archivo 'formulario.php', pasando el 'id' como parámetro.
    fetch("modulos/administracion/profesionales/formulario.php?id=" + id)
        .then((response: Response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text();
        })
        .then((dato: string) => {
            const formularioElement = document.getElementById("formulario");
            if (formularioElement) { // Verificación de nulidad
                formularioElement.style.display = "block";
                formularioElement.innerHTML = dato;
                formularioElement.style.opacity = '0';
                formularioElement.style.transition = 'opacity 0.6s ease-in-out';
                setTimeout(() => {
                    formularioElement.style.opacity = '1';
                }, 10);
            }
        })
        .catch((error: Error) => {
            console.error("Fetch Error loading formulario: ", error);
            const mensajeElement = document.getElementById("mensaje");
            if (mensajeElement) { // Verificación de nulidad
                mensajeElement.style.display = "block";
                mensajeElement.innerHTML =
                    `<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar el formulario: ${error.message}</div>`;
                mensajeElement.style.opacity = '0';
                mensajeElement.style.transition = 'opacity 0.6s ease-in-out';
                setTimeout(() => {
                    mensajeElement.style.opacity = '1';
                }, 10);
            }
        });
}

/**
 * Cierra el formulario de edición/creación.
 */
function cerrar_formulario(): void {
    const formularioElement = document.getElementById("formulario");
    if (formularioElement) { // Verificación de nulidad
        formularioElement.style.opacity = '1';
        formularioElement.style.transition = 'opacity 0.6s ease-in-out';
        formularioElement.style.opacity = '0';
        setTimeout(() => {
            formularioElement.style.display = "none";
            formularioElement.innerHTML = "";
        }, 600);
    }
}

/**
 * Carga el listado de profesionales en una tabla DataTable.
 */
function listado(): void {
    // Desplaza la página al principio de forma instantánea.
    window.scrollTo({
        top: 0,
        behavior: 'auto'
    });

    // Cargar el listado de profesionales.
    fetch("modulos/administracion/profesionales/listado.php")
        .then((response: Response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text();
        })
        .then((dato: string) => {
            const listadoElement = document.getElementById("listado");
            if (listadoElement) { // Verificación de nulidad
                listadoElement.style.display = "block";
                listadoElement.innerHTML = dato;

                listadoElement.style.opacity = '0';
                listadoElement.style.transition = 'opacity 0.6s ease-in-out';
                setTimeout(() => {
                    listadoElement.style.opacity = '1';

                    const dataTableElement = document.getElementById("dataTable");
                    if (dataTableElement && typeof (window as any).DataTable !== 'undefined' && (window as any).DataTable.isDataTable(dataTableElement)) {
                        // Destruye la instancia existente de DataTable.
                        (window as any).DataTable.getInstance(dataTableElement).destroy();
                        console.log("Instancia de DataTable existente destruida.");
                    }

                    if (dataTableElement && typeof (window as any).DataTable !== 'undefined') {
                        new (window as any).DataTable(dataTableElement, {
                            language: {
                                sLengthMenu: "Mostrar _MENU_ registros",
                                sProcessing: "Procesando...",
                                sZeroRecords: "No se encontraron resultados",
                                sEmptyTable: "Ningún dato disponible en esta tabla",
                                sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                                sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                                sInfoPostFix: "",
                                sSearch: "Buscar:",
                                sUrl: "",
                                sInfoThousands: ",",
                                sLoadingRecords: "Cargando...",
                                oPaginate: {
                                    sFirst: "Primero",
                                    sLast: "Último",
                                    sNext: "Siguiente",
                                    sPrevious: "Anterior",
                                },
                            },
                        });
                        console.log("DataTable inicializada.");
                    } else {
                        console.warn("DataTable no está definido o el elemento #dataTable no existe.");
                    }
                }, 600);
            }
        })
        .catch((error: Error) => {
            console.error("Fetch Error loading listado: ", error);
            const mensajeElement = document.getElementById("mensaje");
            if (mensajeElement) { // Verificación de nulidad
                mensajeElement.style.display = "block";
                mensajeElement.innerHTML =
                    `<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar el listado: ${error.message}</div>`;
                mensajeElement.style.opacity = '0';
                mensajeElement.style.transition = 'opacity 0.6s ease-in-out';
                setTimeout(() => {
                    mensajeElement.style.opacity = '1';
                }, 10);
            }
        });
}

/**
 * Valida el formulario usando la API de validación de HTML5.
 * @returns true si el formulario es válido, false en caso contrario.
 */
function validar_formulario(): boolean {
    const form = document.getElementById("form") as HTMLFormElement; // Cast a HTMLFormElement
    if (!form.checkValidity()) {
        form.classList.add("was-validated");
        return false;
    }
    return true;
}

/**
 * Guarda los datos del formulario (crea o actualiza un profesional).
 * @param id El ID del profesional a guardar (0 para un nuevo registro).
 */
function guardar(id: number): void {
    if (!validar_formulario()) {
        return;
    }

    if (confirm("¿Desea Guardar el Registro?")) {
        const formElement = document.getElementById("form") as HTMLFormElement;
        const formData = new FormData(formElement);

        fetch(`modulos/administracion/profesionales/controlador.php?f=editar&id=${id}`, {
            method: "POST",
            body: formData,
        })
            .then((response: Response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.text();
            })
            .then((dato: string) => {
                const mensajeElement = document.getElementById("mensaje");
                if (mensajeElement) { // Verificación de nulidad
                    mensajeElement.style.display = "block";
                    mensajeElement.innerHTML = dato;
                    mensajeElement.style.opacity = '0';
                    mensajeElement.style.transition = 'opacity 0.6s ease-in-out';
                    setTimeout(() => {
                        mensajeElement.style.opacity = '1';
                    }, 10);
                }
            })
            .catch((error: Error) => {
                console.error("Fetch Error saving data: ", error);
                const mensajeElement = document.getElementById("mensaje");
                if (mensajeElement) { // Verificación de nulidad
                    mensajeElement.style.display = "block";
                    mensajeElement.innerHTML =
                        `<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ${error.message}</div>`;
                    mensajeElement.style.opacity = '0';
                    mensajeElement.style.transition = 'opacity 0.6s ease-in-out';
                    setTimeout(() => {
                        mensajeElement.style.opacity = '1';
                    }, 10);
                }
            });
    }
}

/**
 * Elimina un profesional.
 * @param id El ID del profesional a eliminar.
 */
function eliminar(id: number): void {
    if (confirm("¿Desea Eliminar el Profesional?")) {
        fetch(`modulos/administracion/profesionales/controlador.php?f=eliminar&id=${id}`)
            .then((response: Response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.text();
            })
            .then((dato: string) => {
                const mensajeElement = document.getElementById("mensaje");
                if (mensajeElement) { // Verificación de nulidad
                    mensajeElement.style.display = "block";
                    mensajeElement.innerHTML = dato;
                    mensajeElement.style.opacity = '0';
                    mensajeElement.style.transition = 'opacity 0.6s ease-in-out';
                    setTimeout(() => {
                        mensajeElement.style.opacity = '1';
                    }, 10);
                }
            })
            .catch((error: Error) => {
                console.error("Fetch Error deleting data: ", error);
                const mensajeElement = document.getElementById("mensaje");
                if (mensajeElement) { // Verificación de nulidad
                    mensajeElement.style.display = "block";
                    mensajeElement.innerHTML =
                        `<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error en la comunicación con el servidor: ${error.message}</div>`;
                    mensajeElement.style.opacity = '0';
                    mensajeElement.style.transition = 'opacity 0.6s ease-in-out';
                    setTimeout(() => {
                        mensajeElement.style.opacity = '1';
                    }, 10);
                }
            });
    }
}

/**
 * Muestra los datos de un profesional en un modal.
 * @param id El ID del profesional cuyos datos se desean ver.
 */
function ver_datos(id: number): void {
    fetch(`modulos/administracion/profesionales/ver_datos.php?id=${id}`)
        .then((response: Response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text();
        })
        .then((dato: string) => {
            const modalBody = document.querySelector<HTMLElement>("#verDatosModal .modal-body"); // Cast para mejor tipado
            if (modalBody) { // Verificación de nulidad
                modalBody.innerHTML = dato;
                // Para mostrar el modal, asumiendo que Bootstrap JS está cargado
                const verDatosModalElement = document.getElementById('verDatosModal');
                if (verDatosModalElement && (window as any).bootstrap && (window as any).bootstrap.Modal) {
                    const verDatosModal = new (window as any).bootstrap.Modal(verDatosModalElement);
                    verDatosModal.show();
                } else {
                    console.warn("No se pudo inicializar el modal de Bootstrap. Asegúrese de que Bootstrap JS esté cargado.");
                }
            } else {
                console.error("Elemento .modal-body no encontrado en #verDatosModal.");
            }
        })
        .catch((error: Error) => {
            console.error("Fetch Error loading ver_datos: ", error);
            const mensajeElement = document.getElementById("mensaje");
            if (mensajeElement) { // Verificación de nulidad
                mensajeElement.style.display = "block";
                mensajeElement.innerHTML =
                    `<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-triangle"></i> Error al cargar los datos: ${error.message}</div>`;
                mensajeElement.style.opacity = '0';
                mensajeElement.style.transition = 'opacity 0.6s ease-in-out';
                setTimeout(() => {
                    mensajeElement.style.opacity = '1';
                }, 10);
            }
        });
}