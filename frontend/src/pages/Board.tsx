import { useEffect, useState } from "react";
import Button from "@mui/material/Button";
import { DragDropContext } from "react-beautiful-dnd";
import Profile from "./Profile";
import axios_instance from "../utils/Interceptor";
import DraggableList from "../components/DraggableList";

interface Item {
  id: string;
  title: string;
}

const getCards = async () => {
  const response = await axios_instance.get("/cards", {
    params: { board_id: 3 },
  });
  console.log("response data: ", response.data);
  return response.data;
};

const addCard = async () => {
  const board_id = 3;
  const title = Math.random().toString(36).substring(7);
  const index_board = Math.floor(Math.random() * 1000);
  await axios_instance.post("/cards", { board_id, title, index_board });
};

const deleteCard = async (id: number) => {
  await axios_instance.delete("/cards", {
    params: { id },
  });
}

const reorder = (
  list: Iterable<unknown> | ArrayLike<unknown>,
  startIndex: number,
  endIndex: number
) => {
  const result = Array.from(list);
  const [removed] = result.splice(startIndex, 1);
  result.splice(endIndex, 0, removed);

  return result;
};

/**
 * Moves an item from one list to another list.
 */
const move = (
  source: Iterable<unknown> | ArrayLike<unknown>,
  destination: Iterable<unknown> | ArrayLike<unknown>,
  droppableSource: { index: number; droppableId: string | number },
  droppableDestination: { index: number; droppableId: string | number }
) => {
  const sourceClone = Array.from(source);
  const destClone = Array.from(destination);
  const [removed] = sourceClone.splice(droppableSource.index, 1);

  destClone.splice(droppableDestination.index, 0, removed);

  const result = {};
  result[droppableSource.droppableId] = sourceClone;
  result[droppableDestination.droppableId] = destClone;

  return result;
};

export default function BoardPage() {
  const [state, setState] = useState([]);

  useEffect(() => {
    (async () => {
      const cards = await getCards();
      setState([cards]);
    })();
  }, []);

  function onDragEnd(result: {
    source: { droppableId: string; index: number };
    destination: { droppableId: string; index: number };
  }) {
    const { source, destination } = result;

    // dropped outside the list
    if (!destination) {
      return;
    }
    const sInd: number = +source.droppableId;
    const dInd: number = +destination.droppableId;

    if (sInd === dInd) {
      const items = reorder(state[sInd], source.index, destination.index);
      const newState: Partial<Item>[][] = [...state];
      newState[sInd] = items;
      setState(newState as unknown as Item[][]);
    } else {
      const result = move(state[sInd], state[dInd], source, destination);
      const newState = [...state];
      newState[sInd] = result[sInd];
      newState[dInd] = result[dInd];

      setState(newState.filter((group) => group.length));
    }
  }

  return (
    <div>
      <Profile
        email={localStorage.getItem("email")}
        onLogout={() => {
          localStorage.clear();
          window.location.href = "/";
        }}
      />
      <Button
        type="button"
        onClick={() => {
          setState([...state, []]);
        }}
      >
        Add new group
      </Button>

      <Button
        type="button"
        onClick={async () => {
          await addCard();
          const cards = await getCards();
          setState([cards]);
        }}
      >
        Add new item
      </Button>
      <div style={{ display: "flex" }}>
        <DragDropContext onDragEnd={onDragEnd}>
          {state.map((el, ind) => (
            <DraggableList
              key={ind}
              listId={ind}
              items={el}
              onDelete={(listId, index) => {
                deleteCard(state[listId][index].id);
                const newState = [...state];
                newState[listId].splice(index, 1);
                setState(newState.filter((group) => group.length));
              }}
            />
          ))}
        </DragDropContext>
      </div>
    </div>
  );
}
