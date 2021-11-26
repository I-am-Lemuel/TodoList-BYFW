let dragged;
let id;
let index;
let indexDrop;
var list;

document.querySelectorAll(".dropzone > span").forEach((node) => {
  node.ondblclick = (event) => {
    console.log(event.target.innerHTML);
    var input = document.createElement("input");
    input.value = event.target.innerHTML;
    input.onblur = () => {
      node.innerHTML = input.value;
      let formData = new FormData();
      formData.append("title", node.innerHTML);
      formData.append("pos", node.parentNode.id);
      fetch(`/todo/update`, {
        method: "POST",
        ContentType: "multipart/form-data",
        body: formData,
      })
        .then((response) => {
          response;
          return response.text();
        })
        .then((text) => {
          setList();
          return console.log(text);
        });
      console.log(val);
    };
    node.innerHTML = "";
    node.appendChild(input);
    input.focus();
  };
});

const setList = () => {
  list = Array.from(document.querySelectorAll(".dropzone")).map(
    (todo) => todo.id
  );
  console.log(list);
};
setList();

document.addEventListener("dragstart", ({ target }) => {
  dragged = target;
  id = target.id;
  for (let i = 0; i < list.length; i += 1) {
    if (list[i] == id) {
      index = i + 1;
      console.log(`van pos ${index}`);
    }
  }
});

document.addEventListener("dragover", (event) => {
  event.preventDefault();
});

document.addEventListener("drop", ({ target }) => {
  if ((target.parentNode.className == "dropzone" || target.className == "dropzone") && target.id !== id) {
    if (target.parentNode.className == "dropzone" ) {
      target = target.parentNode;
    }
    dragged.remove(dragged);
    for (let i = 0; i < list.length; i += 1) {
      if (list[i] == target.id) {
        indexDrop = i + 1;
        console.log(`naar pos ${indexDrop}`);
      }
    }
    let formData = new FormData();
    formData.append("index", index);
    formData.append("indexDrop", indexDrop);

    fetch(`/todo/move`, {
      method: "POST",
      ContentType: "multipart/form-data",
      body: formData,
    })
      .then((response) => {
        response;
        return response.text();
      })
      .then((text) => {
        setList();
        return console.log(text);
      });
    if (index > indexDrop) {
      target.before(dragged);
    } else {
      target.after(dragged);
    }
  }
});

document.querySelectorAll("#btn-done").forEach(function (node) {
  node.onclick = function () {
    let val = node.className;
    let formData = new FormData();
    formData.append("status", val);
    formData.append("pos", node.parentNode.id);

    fetch(`/todo/toggle`, {
      method: "POST",
      ContentType: "multipart/form-data",
      body: formData,
    })
      .then((response) => {
        response;
        return response.text();
      })
      .then((text) => {
        setList();
        return console.log(text);
      });
    console.log(val);
  };
});
document.querySelectorAll("#btn-remove").forEach(function (node) {
  node.onclick = function () {
    let formData = new FormData();
    formData.append("pos", node.parentNode.id);

    fetch(`/todo/remove`, {
      method: "POST",
      ContentType: "multipart/form-data",
      body: formData,
    })
      .then((response) => {
        response;
        return response.text();
      })
      .then((text) => {
        setList();
        return console.log(text);
      });
  };
});
