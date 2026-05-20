<template>
  <div class="task-card" :class="{ done: task.is_completed }">
    <!-- Priority stripe -->
    <span class="priority-stripe" :class="`stripe-${task.priority?.toLowerCase()}`"></span>

    <div class="task-body">
      <div class="task-top">
        <button class="check-btn" @click="$emit('complete', task.id)" :disabled="task.is_completed" :title="task.is_completed ? 'Completed' : 'Mark as done'">
          <span v-if="task.is_completed" class="check-icon done-icon">✓</span>
          <span v-else class="check-icon pending-icon"></span>
        </button>

        <div class="task-info">
          <div class="task-title" :class="{ striked: task.is_completed }">{{ task.title }}</div>
          <div v-if="task.description" class="task-desc">{{ task.description }}</div>
        </div>
      </div>

      <div class="task-footer">
        <span class="badge" :class="`badge-${task.priority?.toLowerCase()}`">{{ task.priority }}</span>
        <span class="task-date" :class="{ 'date-today': isToday && !task.is_completed }">
          {{ task.is_completed ? '✓ Done' : task.due_date }}
          <span v-if="isToday && !task.is_completed" class="today-flag">TODAY</span>
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  task: { type: Object, required: true },
  isToday: { type: Boolean, default: false }
})
defineEmits(['complete'])
</script>

<style scoped>
.task-card {
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  display: flex;
  transition: border-color 0.15s;
  position: relative;
  overflow: hidden;
}
.task-card:hover { border-color: var(--color-border-strong); }
.task-card.done { opacity: 0.5; }

.priority-stripe {
  width: 4px;
  flex-shrink: 0;
  display: block;
}
.stripe-high   { background: var(--color-danger); }
.stripe-medium { background: var(--color-warn); }
.stripe-low    { background: var(--color-success); }

.task-body {
  flex: 1;
  padding: 14px 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  min-width: 0;
}

.task-top {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.check-btn {
  flex-shrink: 0;
  width: 22px;
  height: 22px;
  border: 2px solid var(--color-border-strong);
  background: var(--color-surface-2);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  margin-top: 2px;
  transition: border-color 0.15s, background 0.15s;
}
.check-btn:not(:disabled):hover {
  border-color: var(--color-accent);
  background: var(--color-accent-dim);
}
.check-btn:disabled { cursor: default; }

.check-icon { font-size: 13px; line-height: 1; }
.done-icon  { color: var(--color-success); font-weight: 700; }
.pending-icon { display: block; width: 8px; height: 8px; background: var(--color-border-strong); }

.task-info { flex: 1; min-width: 0; }
.task-title {
  font-weight: 600;
  font-size: 14px;
  color: var(--color-text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.task-title.striked { text-decoration: line-through; color: var(--color-text-dim); }
.task-desc {
  font-size: 12px;
  color: var(--color-text-muted);
  margin-top: 4px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.task-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}

.task-date {
  font-family: var(--font-mono);
  font-size: 11px;
  color: var(--color-text-muted);
  display: flex;
  align-items: center;
  gap: 6px;
}
.task-date.date-today { color: var(--color-warn); }

.today-flag {
  background: var(--color-warn);
  color: #000;
  font-size: 9px;
  font-weight: 700;
  font-family: var(--font-mono);
  padding: 1px 5px;
  letter-spacing: 0.06em;
}
</style>
