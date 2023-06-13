import React, { CSSProperties, useEffect, useState } from "react";
import Button from "@mui/material/Button";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import Profile from "./Profile";
import axios_instance from "../utils/Interceptor";

interface Item {
  id: string;
  title: string;
}

const getCards = async () => {
  const response = await axios_instance.get("/cards", {params: {board_id: 3}});
  console.log("response data: ", response.data);
  return response.data;
}

const addCards = async () => {
  const board_id = 3;
  const title = Math.random().toString(36).substring(7);
  const index_board = Math.floor(Math.random() * 10);
  await axios_instance.post("/cards", {board_id, title, index_board});
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

const grid = 8;

const getItemStyle = (
  isDragging: boolean,
  draggableStyle: CSSProperties | undefined
) => ({
  padding: grid * 2,
  margin: `0 0 ${grid}px 0`,
  background: isDragging ? "lightgreen" : "white",
  borderRadius: "5px",
  fontFamily: "Roboto",
  color: "dodgerblue",

  // styles we need to apply on draggables
  ...draggableStyle,
});
const getListStyle = (isDraggingOver: boolean) => ({
  background: isDraggingOver ? "lightblue" : "lightgrey",
  padding: grid * 2,
  margin: "0 10px",
  width: 250,
  borderRadius: "5px",

});

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
          await addCards();
          const cards = await getCards();
          setState([cards]);
        }}
      >
        Add new item
      </Button>
      <div style={{ display: "flex" }}>
        <DragDropContext onDragEnd={onDragEnd}>
          {state.map((el, ind) => (
            <Droppable key={ind} droppableId={`${ind}`}>
              {(
                provided: {
                  innerRef: React.LegacyRef<HTMLDivElement>;
                  droppableProps: JSX.IntrinsicAttributes &
                    React.ClassAttributes<HTMLDivElement> &
                    React.HTMLAttributes<HTMLDivElement>;
                  placeholder:
                    | string
                    | number
                    | boolean
                    | React.ReactElement<
                        any,
                        string | React.JSXElementConstructor<any>
                      >
                    | React.ReactFragment
                    | React.ReactPortal;
                },
                snapshot: { isDraggingOver: boolean }
              ) => (
                <div
                  ref={provided.innerRef}
                  style={getListStyle(snapshot.isDraggingOver)}
                  {...provided.droppableProps}
                >
                  {el.map((item, index) => (
                    <Draggable
                      key={item.id.toString()}
                      draggableId={item.id.toString()}
                      index={index}
                    >
                      {(
                        provided: {
                          innerRef: React.LegacyRef<HTMLDivElement>;
                          draggableProps: JSX.IntrinsicAttributes &
                            React.ClassAttributes<HTMLDivElement> &
                            React.HTMLAttributes<HTMLDivElement>;
                          dragHandleProps: JSX.IntrinsicAttributes &
                            React.ClassAttributes<HTMLDivElement> &
                            React.HTMLAttributes<HTMLDivElement>;
                        },
                        snapshot: { isDragging: boolean }
                      ) => (
                        <div
                          ref={provided.innerRef}
                          {...provided.draggableProps}
                          {...provided.dragHandleProps}
                          style={{
                            ...getItemStyle(
                              snapshot.isDragging,
                              provided.draggableProps.style
                            ),
                            userSelect: "none",
                          }}
                        >
                          <div
                            style={{
                              display: "flex",
                              justifyContent: "space-around",
                            }}
                          >
                            {item.title}
                            <Button
                              type="button"
                              color="error"
                              variant="contained"
                              onClick={() => {
                                const newState = [...state];
                                newState[ind].splice(index, 1);
                                setState(
                                  newState.filter((group) => group.length)
                                );
                              }}
                            >
                              delete
                            </Button>
                          </div>
                        </div>
                      )}
                    </Draggable>
                  ))}
                  {provided.placeholder}
                </div>
              )}
            </Droppable>
          ))}
        </DragDropContext>
      </div>
    </div>
  );
}
