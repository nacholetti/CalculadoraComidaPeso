// ===== Config que inyecta Blade =====
const checkoutUrl = (typeof window !== "undefined" && window.checkoutUrl) ? window.checkoutUrl : "/checkout";
const csrfToken   = (typeof window !== "undefined" && window.csrfToken)   ? window.csrfToken   : "";

// Evitar doble inicialización si el script se incluye dos veces
if (!window.__tiendaInit) {
  window.__tiendaInit = true;

  // ===== Estado & persistencia =====
  const CART_KEY = "carrito_cliente_v1";
  // Map key=id ("comida-5"), value={id,tipo,producto_id,nombre,precio,costo,qty}
  const carrito = new Map();

  function cargarCarrito() {
    try {
      const raw = localStorage.getItem(CART_KEY);
      if (!raw) return;
      const arr = JSON.parse(raw);
      arr.forEach(it => it?.id && carrito.set(it.id, it));
    } catch (e) {
      console.warn("[carrito] no se pudo cargar", e);
    }
  }
  function guardarCarrito() {
    localStorage.setItem(CART_KEY, JSON.stringify(Array.from(carrito.values())));
  }

  // ===== Util =====
  function el(id) { return document.getElementById(id); }
  function escapeHtml(str) {
    return (str ?? "").toString()
      .replace(/&/g,"&amp;").replace(/</g,"&lt;")
      .replace(/>/g,"&gt;").replace(/"/g,"&quot;")
      .replace(/'/g,"&#039;");
  }

  // ===== Render seguro =====
  function renderCarrito() {
    const lista  = el("carrito-lista");
    const btnFin = el("btn-finalizar");
    const itEl   = el("carrito-items");
    const totEl  = el("carrito-total");
    const ganEl  = el("carrito-ganancia");

    if (!lista || !btnFin || !itEl || !totEl || !ganEl) return;

    let total = 0, ganancia = 0, items = 0;
    const nodes = [];

    if (carrito.size === 0) {
      const vacio = document.createElement("div");
      vacio.className = "text-muted";
      vacio.id = "carrito-vacio";
      vacio.textContent = "No agregaste productos.";
      nodes.push(vacio);
      btnFin.disabled = true;
    } else {
      btnFin.disabled = false;

      for (const [key, it] of carrito.entries()) {
        const precio = Number(it.precio) || 0;
        const costo  = Number(it.costo)  || 0;
        const qty    = Number(it.qty)    || 0;

        total    += precio * qty;
        ganancia += (precio - costo) * qty;
        items    += qty;

        const row = document.createElement("div");
        row.className = "d-flex align-items-center justify-content-between border rounded p-2";
        row.innerHTML = `
          <div class="me-2">
            <div><strong>${escapeHtml(it.nombre)}</strong></div>
            <div class="text-muted">Precio: $${precio.toFixed(2)} · Costo: $${costo.toFixed(2)}</div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-secondary qty-btn" data-action="qty" data-key="${key}" data-delta="-1">−</button>
            <span>${qty}</span>
            <button class="btn btn-sm btn-outline-secondary qty-btn" data-action="qty" data-key="${key}" data-delta="1">+</button>
            <button class="btn btn-sm btn-outline-danger" data-action="remove" data-key="${key}">Quitar</button>
          </div>
        `;
        nodes.push(row);
      }
    }

    // Reemplaza todo el contenido de una
    lista.replaceChildren(...nodes);

    itEl.textContent  = items;
    totEl.textContent = total.toFixed(2);
    ganEl.textContent = ganancia.toFixed(2);

    // console.log("[carrito] render ok", {items, total, ganancia});
  }

  // ===== Mutadores (render primero, luego persistir) =====
  function agregarAlCarrito(item) {
    const key = item.id;
    if (!carrito.has(key)) carrito.set(key, { ...item, qty: 1 });
    else                   carrito.get(key).qty += 1;
    renderCarrito();
    guardarCarrito();
  }
  function cambiarCantidad(key, delta) {
    if (!carrito.has(key)) return;
    carrito.get(key).qty += delta;
    if (carrito.get(key).qty <= 0) carrito.delete(key);
    renderCarrito();
    guardarCarrito();
  }
  function eliminarItem(key) {
    carrito.delete(key);
    renderCarrito();
    guardarCarrito();
  }

  // ===== Finalizar compra =====
  async function finalizarCompra() {
    if (carrito.size === 0) return;
    const items = Array.from(carrito.values()).map(it => ({
      tipo: it.tipo,
      producto_id: it.producto_id,
      qty: it.qty
    }));
    try {
      const res = await fetch(checkoutUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
          "Accept": "application/json"
        },
        body: JSON.stringify({ items })
      });
      if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        throw new Error(err.message || "Error en checkout");
      }
      const data = await res.json();
      carrito.clear(); guardarCarrito(); renderCarrito();
      alert(`Orden #${data.order_id} creada. Total: $${Number(data.total).toFixed(2)}`);
    } catch (e) {
      console.error(e);
      alert("No se pudo finalizar la compra.");
    }
  }

  // ===== Delegación de eventos (1 sola) =====
  document.addEventListener("DOMContentLoaded", () => {
    cargarCarrito();
    renderCarrito();

    document.body.addEventListener("click", (e) => {
      const addBtn = e.target.closest(".add-to-cart");
      if (addBtn) {
        e.preventDefault();
        const id          = addBtn.dataset.id;
        const tipo        = addBtn.dataset.tipo;
        const producto_id = Number(addBtn.dataset.productoId || 0);
        const nombre      = addBtn.dataset.nombre || "";
        const precio      = Number(addBtn.dataset.precio || 0);
        const costo       = Number(addBtn.dataset.costo  || 0);
        if (!id || !producto_id) return;
        agregarAlCarrito({ id, tipo, producto_id, nombre, precio, costo });
        return;
      }

      const act = e.target.dataset.action;
      if (act === "qty") {
        e.preventDefault();
        const key = e.target.dataset.key;
        const delta = Number(e.target.dataset.delta || 0);
        cambiarCantidad(key, delta);
        return;
      }
      if (act === "remove") {
        e.preventDefault();
        const key = e.target.dataset.key;
        eliminarItem(key);
        return;
      }
      if (e.target.id === "btn-finalizar") {
        e.preventDefault();
        finalizarCompra();
      }
    }, { capture: false, passive: false });
  });

  // Exporto para compatibilidad si tenías onclicks legacy
  window.cambiarCantidad = cambiarCantidad;
  window.eliminarItem    = eliminarItem;
  window.finalizarCompra = finalizarCompra;
}
