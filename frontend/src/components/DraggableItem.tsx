import { Button } from "@mui/material";
import { CSSProperties } from "react";

interface Item {
    id: string;
    title: string;
}

interface DraggableItemProps {
    item: Item;
    index: number;
    isDragging: boolean;
    provided: {
      innerRef: React.LegacyRef<HTMLDivElement>;
      // The interection below is used to make draggable items accepts any valid HTML or React element -> Making it adaptable to dnd events;
      draggableProps: JSX.IntrinsicAttributes &
        React.ClassAttributes<HTMLDivElement> &
        React.HTMLAttributes<HTMLDivElement>;
      dragHandleProps: JSX.IntrinsicAttributes &
        React.ClassAttributes<HTMLDivElement> &
        React.HTMLAttributes<HTMLDivElement>;
    };
    onDelete: () => void;
  }
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
  
const DraggableItem: React.FC<DraggableItemProps> = ({
    item,
    index,
    isDragging,
    provided,
    onDelete,
  }) => (
    <div
      ref={provided.innerRef}
      {...provided.draggableProps}
      {...provided.dragHandleProps}
      style={{
        ...getItemStyle(isDragging, provided.draggableProps.style),
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
          onClick={onDelete}
        >
          delete
        </Button>
      </div>
    </div>
);
export default DraggableItem;
  