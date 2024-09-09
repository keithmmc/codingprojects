class Task:
    def __init__(self, name, burst_time):
        self.name = name
        self.burst_time = burst_time

def round_robin(tasks, quantum):
    time = 0  # Keep track of total time passed
    
    while tasks:
        for task in tasks[:]:
            if task.burst_time > quantum:
                print(f"Task {task.name} runs for {quantum} units")
                task.burst_time -= quantum
                time += quantum
            else:
                # Task finishes
                print(f"Task {task.name} runs for {task.burst_time} units and completes")
                time += task.burst_time
                tasks.remove(task)  # Remove the finished task
    print(f"All tasks completed in {time} units of time")

if __name__ == "__main__":
    # Define some tasks with burst times
    tasks = [
        Task("Task 1", 10),
        Task("Task 2", 5),
        Task("Task 3", 8),
    ]
    
    quantum = 3  # Time quantum
    print(f"Scheduling tasks with time quantum of {quantum}")
    round_robin(tasks, quantum)
