"use strict"

var state = window.sessionStorage,
    NROW = parseInt(state.getItem("NROW")) || 2,
    NCOL = parseInt(state.getItem("NCOL")) || 2,
    table = document.getElementById("table");

window.onload = function() {
    newButton("Add Row", function() { changeRow(+1) });
    newButton("Add Col", function() { changeCol(+1) });
    newButton("Submit");
    newButton("Reset", function() { resetState(2, 2) });
    syncAll();
}

function resetState(nRow, nCol) {
    NROW = nRow;
    NCOL = nCol;
    syncAll();
}

function newButton(label, callback) {
    var btn = document.createElement("button");
    btn.innerHTML = label;
    btn.onclick = callback;
    table.appendChild(btn);
}

function changeRow() {
    NROW += 1;
    syncAll();
}

function changeCol() {
    NCOL += 1;
    syncAll();
}

function newCell(cellId) {
    var cell = document.createElement("div");
    cell.className = "cell green";
    cell.id = cellId;
    cell.onclick = function() {
        cell.classList.toggle("red");
        cell.classList.toggle("green");
    }
    return cell
}

function newRow(rowId) {
    var row = document.createElement("div"),
        container = document.createElement("div"),
        textInput = document.createElement("input");
    row.className = "row";
    row.id = rowId;

    textInput.id = rowId+"header";
    textInput.value = "Item #"+(parseInt(rowId.slice(1), 10)+1);
    var delBtn = document.createElement("button");
    delBtn.innerHTML = "-";
    delBtn.onclick = function() { console.log(row) }
    container.appendChild(delBtn);
    container.appendChild(textInput);

    container.className = "inputContainer";
    row.appendChild(container);
    return row
}

function syncAll() {
    // sync with session
    state.setItem("NROW", NROW);
    state.setItem("NCOL", NCOL);

    // sync with display
    for (var r=0;r<NROW;r++) {
        var rowId = "r"+r,
            row = document.getElementById(rowId);

        if (row == undefined) { row = newRow(rowId) }

        table.appendChild(row);

        for (var c=0;c<NCOL;c++) {
            var cellId = "r"+r+"c"+c,
                cell = document.getElementById(cellId);

            if (cell == undefined) { cell = newCell(cellId) }

            row.appendChild(cell);

        }
    }
}
