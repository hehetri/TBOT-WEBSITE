#!/usr/bin/env python
"""GUI editor for item-2.bin records."""
from __future__ import annotations

import tkinter as tk
from dataclasses import replace
from pathlib import Path
from tkinter import filedialog, messagebox, ttk

import item_editor


class ItemEditorApp(tk.Tk):
    def __init__(self) -> None:
        super().__init__()
        self.title("Item Editor")
        self.geometry("980x600")
        self.minsize(900, 500)

        self.file_path: Path | None = None
        self.data: bytearray | None = None
        self.records: list[item_editor.ItemRecord] = []
        self.current_index: int | None = None

        self._build_ui()

    def _build_ui(self) -> None:
        toolbar = ttk.Frame(self)
        toolbar.pack(fill="x", padx=8, pady=6)

        ttk.Button(toolbar, text="Abrir", command=self.open_file).pack(side="left")
        ttk.Button(toolbar, text="Salvar", command=self.save_file).pack(
            side="left", padx=(6, 0)
        )
        ttk.Button(toolbar, text="Salvar como", command=self.save_file_as).pack(
            side="left", padx=(6, 0)
        )

        self.status_var = tk.StringVar(value="Nenhum arquivo carregado.")
        ttk.Label(toolbar, textvariable=self.status_var).pack(side="right")

        main = ttk.Frame(self)
        main.pack(fill="both", expand=True, padx=8, pady=8)

        self.listbox = tk.Listbox(main, width=30)
        self.listbox.pack(side="left", fill="y")
        self.listbox.bind("<<ListboxSelect>>", self.on_select)

        details = ttk.Frame(main)
        details.pack(side="left", fill="both", expand=True, padx=(10, 0))

        form = ttk.Frame(details)
        form.pack(fill="x", pady=(0, 10))

        ttk.Label(form, text="Nome").grid(row=0, column=0, sticky="w")
        self.name_var = tk.StringVar()
        ttk.Entry(form, textvariable=self.name_var, width=50).grid(
            row=0, column=1, sticky="ew", padx=6
        )

        ttk.Label(form, text="Descrição").grid(row=1, column=0, sticky="w")
        self.desc_var = tk.StringVar()
        ttk.Entry(form, textvariable=self.desc_var, width=50).grid(
            row=1, column=1, sticky="ew", padx=6
        )

        ttk.Label(form, text="Stats (hex)").grid(row=2, column=0, sticky="w")
        self.stats_var = tk.StringVar()
        ttk.Entry(form, textvariable=self.stats_var, width=50).grid(
            row=2, column=1, sticky="ew", padx=6
        )

        ttk.Label(form, text="Tail (hex)").grid(row=3, column=0, sticky="w")
        self.tail_var = tk.StringVar()
        ttk.Entry(form, textvariable=self.tail_var, width=50).grid(
            row=3, column=1, sticky="ew", padx=6
        )

        form.columnconfigure(1, weight=1)

        ttk.Button(details, text="Atualizar item", command=self.update_item).pack(
            anchor="w"
        )

        self.preview = tk.Text(details, height=10, state="disabled")
        self.preview.pack(fill="both", expand=True, pady=(10, 0))

    def open_file(self) -> None:
        path = filedialog.askopenfilename(
            title="Selecionar item-2.bin",
            filetypes=[("Item binary", "*.bin"), ("All files", "*")],
        )
        if not path:
            return
        self.load_path(Path(path))

    def load_path(self, path: Path) -> None:
        try:
            data = bytearray(item_editor.load_file(path))
            _, _, records = item_editor._read_records(data)
        except Exception as exc:  # noqa: BLE001
            messagebox.showerror("Erro", f"Falha ao abrir: {exc}")
            return
        self.file_path = path
        self.data = data
        self.records = list(records)
        self.current_index = None
        self._refresh_list()
        self.status_var.set(f"Arquivo: {path}")

    def _refresh_list(self) -> None:
        self.listbox.delete(0, tk.END)
        for rec in self.records:
            self.listbox.insert(tk.END, f"{rec.index:4d}  {rec.name}")
        self._clear_form()

    def _clear_form(self) -> None:
        self.name_var.set("")
        self.desc_var.set("")
        self.stats_var.set("")
        self.tail_var.set("")
        self._set_preview("Selecione um item para ver detalhes.")

    def on_select(self, _event: tk.Event) -> None:
        selection = self.listbox.curselection()
        if not selection:
            return
        index = selection[0]
        record = self.records[index]
        self.current_index = record.index
        self.name_var.set(record.name)
        self.desc_var.set(record.description)
        self.stats_var.set(record.stats.hex())
        self.tail_var.set(record.tail.hex())
        self._set_preview(self._format_preview(record))

    def _format_preview(self, record: item_editor.ItemRecord) -> str:
        return (
            f"Index: {record.index}\n"
            f"Nome: {record.name}\n"
            f"Descrição: {record.description}\n"
            f"Stats: {record.stats.hex()}\n"
            f"Tail: {record.tail.hex()}\n"
        )

    def update_item(self) -> None:
        if self.data is None or self.current_index is None:
            messagebox.showwarning("Aviso", "Nenhum item selecionado.")
            return
        try:
            name = self.name_var.get()
            description = self.desc_var.get()
            stats = item_editor._parse_hex(
                self.stats_var.get(), item_editor.STATS_SIZE
            )
            tail = item_editor._parse_hex(
                self.tail_var.get(), item_editor.TAIL_SIZE
            )
        except Exception as exc:  # noqa: BLE001
            messagebox.showerror("Erro", f"Dados inválidos: {exc}")
            return

        record = self.records[self.current_index]
        updated = replace(
            record,
            name=name,
            description=description,
            stats=stats,
            tail=tail,
        )
        self.records[self.current_index] = updated
        item_editor._write_record(self.data, updated)
        self.listbox.delete(self.current_index)
        self.listbox.insert(self.current_index, f"{updated.index:4d}  {updated.name}")
        self.listbox.selection_set(self.current_index)
        self._set_preview(self._format_preview(updated))

    def save_file(self) -> None:
        if self.file_path is None or self.data is None:
            messagebox.showwarning("Aviso", "Nenhum arquivo carregado.")
            return
        item_editor.save_file(self.file_path, self.data)
        messagebox.showinfo("Salvo", f"Arquivo salvo em {self.file_path}")

    def save_file_as(self) -> None:
        if self.data is None:
            messagebox.showwarning("Aviso", "Nenhum arquivo carregado.")
            return
        path = filedialog.asksaveasfilename(
            title="Salvar como",
            defaultextension=".bin",
            filetypes=[("Item binary", "*.bin"), ("All files", "*")],
        )
        if not path:
            return
        out_path = Path(path)
        item_editor.save_file(out_path, self.data)
        messagebox.showinfo("Salvo", f"Arquivo salvo em {out_path}")

    def _set_preview(self, text: str) -> None:
        self.preview.configure(state="normal")
        self.preview.delete("1.0", tk.END)
        self.preview.insert(tk.END, text)
        self.preview.configure(state="disabled")


def main() -> None:
    app = ItemEditorApp()
    default = Path("item-2.bin")
    if default.exists():
        app.load_path(default)
    app.mainloop()


if __name__ == "__main__":
    main()