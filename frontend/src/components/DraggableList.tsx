import { Droppable, Draggable } from "react-beautiful-dnd";
import DraggableItem from "./DraggableItem";

interface Item {
    id: string;
    title: string;
}

interface DraggableListProps {
  items: Item[];
  listId: string | number;
  onDelete: (listId?: string | number, index?: number) => void;
}

const grid = 8;

const getListStyle = (isDraggingOver: boolean) => ({
    background: isDraggingOver ? "lightblue" : "lightgrey",
    padding: grid * 2,
    margin: "0 10px",
    width: 250,
    borderRadius: "5px",
  
  });

const DraggableList: React.FC<DraggableListProps> = ({ items, listId, onDelete }) => (
  <Droppable droppableId={`${listId}`}>
    {(provided, snapshot) => (
      <div
        ref={provided.innerRef}
        style={getListStyle(snapshot.isDraggingOver)}
        {...provided.droppableProps}
      >
        {items.map((item, index) => (
          <Draggable key={item.id.toString()} draggableId={item.id.toString()} index={index}>
            {(provided, snapshot) => (
              <DraggableItem
                item={item}
                index={index}
                isDragging={snapshot.isDragging}
                provided={provided}
                onDelete={() => onDelete(listId, index)}
              />
            )}
          </Draggable>
        ))}
        {provided.placeholder}
      </div>
    )}
  </Droppable>
);

export default DraggableList;
