import json
import sys
from dataclasses import dataclass, asdict
from pathlib import Path

from PyQt5.QtCore import Qt, QPointF, QRectF
from PyQt5.QtGui import QBrush, QColor, QPen
from PyQt5.QtWidgets import (
    QAction,
    QApplication,
    QFileDialog,
    QGraphicsEllipseItem,
    QGraphicsRectItem,
    QGraphicsScene,
    QGraphicsView,
    QInputDialog,
    QMainWindow,
    QMessageBox,
    QToolBar,
)


@dataclass
class SpawnPoint:
    spawn_id: int
    x: float
    y: float


@dataclass
class Block:
    block_id: int
    x: float
    y: float
    width: float
    height: float
    triggers: list


class SpawnItem(QGraphicsEllipseItem):
    def __init__(self, spawn_id: int, x: float, y: float, radius: float = 6.0):
        super().__init__(-radius, -radius, radius * 2, radius * 2)
        self.setBrush(QBrush(QColor(0, 200, 255)))
        self.setPen(QPen(Qt.black, 1))
        self.setFlag(QGraphicsEllipseItem.ItemIsMovable, True)
        self.setFlag(QGraphicsEllipseItem.ItemIsSelectable, True)
        self.setPos(QPointF(x, y))
        self.spawn_id = spawn_id

    def mouseDoubleClickEvent(self, event):
        new_id, ok = QInputDialog.getInt(
            None, "Edit Spawn", "Spawn ID:", self.spawn_id, 0, 999999
        )
        if ok:
            self.spawn_id = new_id
        super().mouseDoubleClickEvent(event)


class BlockItem(QGraphicsRectItem):
    def __init__(self, block_id: int, rect: QRectF, triggers=None):
        super().__init__(rect)
        self.setBrush(QBrush(QColor(255, 200, 0, 80)))
        self.setPen(QPen(QColor(255, 160, 0), 2))
        self.setFlag(QGraphicsRectItem.ItemIsMovable, True)
        self.setFlag(QGraphicsRectItem.ItemIsSelectable, True)
        self.block_id = block_id
        self.triggers = triggers or []

    def mouseDoubleClickEvent(self, event):
        block_id, ok = QInputDialog.getInt(
            None, "Edit Block", "Block ID:", self.block_id, 0, 999999
        )
        if ok:
            self.block_id = block_id
        trigger_text, ok = QInputDialog.getText(
            None,
            "Edit Block Triggers",
            "Trigger IDs (comma separated):",
            text=",".join(map(str, self.triggers)),
        )
        if ok:
            if trigger_text.strip():
                self.triggers = [
                    int(t.strip())
                    for t in trigger_text.split(",")
                    if t.strip().isdigit()
                ]
            else:
                self.triggers = []
        super().mouseDoubleClickEvent(event)


class DungeonScene(QGraphicsScene):
    def __init__(self, grid_size=32, grid_width=30, grid_height=20):
        super().__init__()
        self.grid_size = grid_size
        self.grid_width = grid_width
        self.grid_height = grid_height
        self.mode = "select"
        self._draw_grid()
        self._drag_start = None
        self._drag_rect_item = None
        self._next_spawn_id = 0
        self._next_block_id = 0

    def _draw_grid(self):
        self.clear()
        width = self.grid_width * self.grid_size
        height = self.grid_height * self.grid_size
        self.setSceneRect(0, 0, width, height)
        grid_pen = QPen(QColor(200, 200, 200), 1)
        for x in range(0, width + 1, self.grid_size):
            self.addLine(x, 0, x, height, grid_pen)
        for y in range(0, height + 1, self.grid_size):
            self.addLine(0, y, width, y, grid_pen)

    def set_mode(self, mode: str):
        self.mode = mode

    def mousePressEvent(self, event):
        if self.mode == "spawn" and event.button() == Qt.LeftButton:
            pos = event.scenePos()
            spawn = SpawnItem(self._next_spawn_id, pos.x(), pos.y())
            self._next_spawn_id += 1
            self.addItem(spawn)
            event.accept()
            return

        if self.mode == "block" and event.button() == Qt.LeftButton:
            self._drag_start = event.scenePos()
            rect = QRectF(self._drag_start, self._drag_start)
            self._drag_rect_item = QGraphicsRectItem(rect)
            self._drag_rect_item.setPen(QPen(QColor(255, 120, 0), 1, Qt.DashLine))
            self.addItem(self._drag_rect_item)
            event.accept()
            return

        super().mousePressEvent(event)

    def mouseMoveEvent(self, event):
        if self.mode == "block" and self._drag_rect_item:
            rect = QRectF(self._drag_start, event.scenePos()).normalized()
            self._drag_rect_item.setRect(rect)
            event.accept()
            return
        super().mouseMoveEvent(event)

    def mouseReleaseEvent(self, event):
        if self.mode == "block" and self._drag_rect_item:
            rect = self._drag_rect_item.rect().normalized()
            self.removeItem(self._drag_rect_item)
            self._drag_rect_item = None
            if rect.width() > 1 and rect.height() > 1:
                block = BlockItem(self._next_block_id, rect)
                self._next_block_id += 1
                self.addItem(block)
            event.accept()
            return
        super().mouseReleaseEvent(event)

    def keyPressEvent(self, event):
        if event.key() == Qt.Key_Delete:
            for item in self.selectedItems():
                self.removeItem(item)
            event.accept()
            return
        super().keyPressEvent(event)

    def set_grid(self, size: int, width: int, height: int):
        self.grid_size = size
        self.grid_width = width
        self.grid_height = height
        items = [item for item in self.items() if isinstance(item, (SpawnItem, BlockItem))]
        self._draw_grid()
        for item in items:
            self.addItem(item)

    def to_project_data(self):
        spawns = []
        blocks = []
        for item in self.items():
            if isinstance(item, SpawnItem):
                pos = item.scenePos()
                spawns.append(asdict(SpawnPoint(item.spawn_id, pos.x(), pos.y())))
            if isinstance(item, BlockItem):
                rect = item.rect().translated(item.pos())
                blocks.append(
                    asdict(
                        Block(
                            item.block_id,
                            rect.x(),
                            rect.y(),
                            rect.width(),
                            rect.height(),
                            list(item.triggers),
                        )
                    )
                )
        return {
            "grid": {
                "cell_size": self.grid_size,
                "width": self.grid_width,
                "height": self.grid_height,
            },
            "spawns": spawns,
            "blocks": blocks,
        }

    def load_project_data(self, data):
        grid = data.get("grid", {})
        self.set_grid(
            grid.get("cell_size", self.grid_size),
            grid.get("width", self.grid_width),
            grid.get("height", self.grid_height),
        )
        for item in [i for i in self.items() if isinstance(i, (SpawnItem, BlockItem))]:
            self.removeItem(item)
        self._next_spawn_id = 0
        self._next_block_id = 0
        for spawn in data.get("spawns", []):
            spawn_item = SpawnItem(spawn["spawn_id"], spawn["x"], spawn["y"])
            self._next_spawn_id = max(self._next_spawn_id, spawn["spawn_id"] + 1)
            self.addItem(spawn_item)
        for block in data.get("blocks", []):
            rect = QRectF(block["x"], block["y"], block["width"], block["height"])
            block_item = BlockItem(block["block_id"], rect, block.get("triggers", []))
            self._next_block_id = max(self._next_block_id, block["block_id"] + 1)
            self.addItem(block_item)

    def export_runtime(self):
        spawns = []
        blocks = []
        for item in self.items():
            if isinstance(item, SpawnItem):
                pos = item.scenePos()
                col = int(pos.x() // self.grid_size)
                row = int(pos.y() // self.grid_size)
                spawns.append({"spawn_id": item.spawn_id, "cell": [col, row]})
            if isinstance(item, BlockItem):
                rect = item.rect().translated(item.pos())
                left = int(rect.left() // self.grid_size)
                top = int(rect.top() // self.grid_size)
                right = int((rect.right()) // self.grid_size)
                bottom = int((rect.bottom()) // self.grid_size)
                blocks.append(
                    {
                        "block_id": item.block_id,
                        "rect": [left, top, right, bottom],
                        "triggers": list(item.triggers),
                    }
                )
        return {"spawns": spawns, "blocks": blocks}


class DungeonEditor(QMainWindow):
    def __init__(self):
        super().__init__()
        self.setWindowTitle("Dungeon Map Editor")
        self.scene = DungeonScene()
        self.view = QGraphicsView(self.scene)
        self.setCentralWidget(self.view)
        self._setup_toolbar()
        self._setup_menu()

    def _setup_toolbar(self):
        toolbar = QToolBar("Tools")
        self.addToolBar(toolbar)

        select_action = QAction("Select", self)
        select_action.triggered.connect(lambda: self.scene.set_mode("select"))
        toolbar.addAction(select_action)

        spawn_action = QAction("Spawn", self)
        spawn_action.triggered.connect(lambda: self.scene.set_mode("spawn"))
        toolbar.addAction(spawn_action)

        block_action = QAction("Block", self)
        block_action.triggered.connect(lambda: self.scene.set_mode("block"))
        toolbar.addAction(block_action)

    def _setup_menu(self):
        menu = self.menuBar()
        file_menu = menu.addMenu("File")

        new_action = QAction("New", self)
        new_action.triggered.connect(self.new_project)
        file_menu.addAction(new_action)

        open_action = QAction("Open Project", self)
        open_action.triggered.connect(self.open_project)
        file_menu.addAction(open_action)

        save_action = QAction("Save Project", self)
        save_action.triggered.connect(self.save_project)
        file_menu.addAction(save_action)

        export_action = QAction("Export Runtime", self)
        export_action.triggered.connect(self.export_runtime)
        file_menu.addAction(export_action)

    def new_project(self):
        self.scene.load_project_data({"grid": {"cell_size": 32, "width": 30, "height": 20}})

    def open_project(self):
        path, _ = QFileDialog.getOpenFileName(
            self, "Open Project", "", "Dungeon Project (*.json)"
        )
        if not path:
            return
        data = json.loads(Path(path).read_text(encoding="utf-8"))
        self.scene.load_project_data(data)

    def save_project(self):
        path, _ = QFileDialog.getSaveFileName(
            self, "Save Project", "", "Dungeon Project (*.json)"
        )
        if not path:
            return
        data = self.scene.to_project_data()
        Path(path).write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")

    def export_runtime(self):
        path, _ = QFileDialog.getSaveFileName(
            self, "Export Runtime", "", "Runtime JSON (*.json)"
        )
        if not path:
            return
        data = self.scene.export_runtime()
        Path(path).write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")
        QMessageBox.information(self, "Export Complete", "Runtime JSON exported.")


def main():
    app = QApplication(sys.argv)
    editor = DungeonEditor()
    editor.resize(960, 720)
    editor.show()
    sys.exit(app.exec_())


if __name__ == "__main__":
    main()